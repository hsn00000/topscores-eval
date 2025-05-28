<?php

namespace App\Controller;

use App\Entity\Stream;
use App\Form\StreamType;
use App\Entity\Evaluation;
use App\Form\EvaluationType;
use App\Repository\StreamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/stream')]
#[IsGranted('ROLE_USER')]
class StreamController extends AbstractController
{
    #[Route('/', name: 'app_stream_index', methods: ['GET'])]
    public function index(StreamRepository $streamRepository): Response
    {
        return $this->render('stream/index.html.twig', [
            'streams' => $streamRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/demain', name: 'app_stream_tomorrow')]
    public function streamTomorrow(StreamRepository $streamRepository): Response
    {
        //$tomorrow = new \DateTime('tomorrow');

        $streams = $streamRepository->findStreamsTomorrow();

        //$streams = array_filter($streams, function (Stream $stream) use ($tomorrow) {
        //    return $stream->getDateStart()->format('Y-m-d') === $tomorrow->format('Y-m-d');
        //});
        /*$streamTomorrow = [];
        foreach ($streams as $stream) {
        
            if ($stream->getDateStart()->format('Y-m-d') === $tomorrow->format('Y-m-d')) {
                $streamTomorrow[] = $stream;
            }
        }*/

        return $this->render('stream/tomorrow.html.twig', [
            'streams' => $streams,
        ]);
    }

    #[Route('/new', name: 'app_stream_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stream = new Stream();
        $stream->setUser($this->getUser());
        $form = $this->createForm(StreamType::class, $stream);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stream);
            $entityManager->flush();

            return $this->redirectToRoute('app_stream_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stream/new.html.twig', [
            'stream' => $stream,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stream_show', methods: ['GET', 'POST'])]
    public function show(Stream $stream, Request $request, EntityManagerInterface $entityManager): Response
    {
        //Initialisation du formualaire d'évaluation
        $evaluation = new Evaluation();
        $evaluation->setStream($stream);
        $evaluation->setUser($this->getUser());

        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($evaluation);
            $entityManager->flush();
            $this->addFlash('success', 'Votre évaluation a été enregistrée avec succès !');
        }

        $evaluations = $stream->getEvaluations();

        return $this->render('stream/show.html.twig', [
            'stream' => $stream,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stream_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stream $stream, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StreamType::class, $stream);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stream_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stream/edit.html.twig', [
            'stream' => $stream,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stream_delete', methods: ['POST'])]
    public function delete(Request $request, Stream $stream, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stream->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($stream);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stream_index', [], Response::HTTP_SEE_OTHER);
    }

    
}
