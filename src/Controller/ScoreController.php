<?php

namespace App\Controller;

use App\Entity\Jeu;
use App\Entity\Partie;
use App\Form\SelectJeuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ScoreController extends AbstractController
{
    #[Route('/score/{id}', name: 'app_score_show')]
    public function show(EntityManagerInterface $entityManager, Request $request, $id): Response
    {
        $formSelectJeu = $this->createForm(SelectJeuType::class);
        $formSelectJeu->handleRequest($request);

        $jeu = $entityManager->getRepository(Jeu::class)->find($id);

        if ($formSelectJeu->isSubmitted() && $formSelectJeu->isValid()) {
            $jeu = $formSelectJeu->get('jeux')->getData();
            return $this->redirectToRoute('app_score_show', ['id' => $jeu->getId()]);
        }

        //option 1: get scores from the repository
        $scores = $entityManager->getRepository(Partie::class)->findScoresByGameCurrentMonth($jeu);

        //option 2: get all scores and filter them
        /*$scores = $entityManager->getRepository(Partie::class)->findBy(['jeu' => $jeu]);

        //order score by desc
        usort($scores, function($a, $b) {
            return $b->getScore() <=> $a->getScore();
        });
        //keep only the scores of the current month
        $scores = array_filter($scores, function($score) {
            $date = new \DateTime();
            $date->modify('first day of this month');
            $date->setTime(0, 0, 0);
            $date2 = new \DateTime();
            $date2->modify('last day of this month');
            $date2->setTime(23, 59, 59);
            return $score->getDate() >= $date && $score->getDate() <= $date2;
        });*/

        $first = null;
        $second = null;
        $third = null;

        $i = 0;
        foreach ($scores as $key => $score) {
            if ($i == 0) {
                $first = $score;
            }
            if ($i == 1) {
                $second = $score;
            }
            if ($i == 2) {
                $third = $score;
            }
            $i++;
        }

        return $this->render('score/index.html.twig', [
            'formSelectJeu' => $formSelectJeu,
            'jeu' => $jeu,
            'scores' => $scores,
            'first' => $first,
            'second' => $second,
            'third' => $third,
        ]);
    }
}
