<?php

namespace App\Form;

use App\Entity\Evaluation;
use App\Entity\Stream;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaire', TextType::class, [
                'label' => 'Commentaire',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Votre commentaire (optionnel)'
                ],
            ])
            ->add('note', NumberType::class, [
                'label' => 'Note',
                'attr' => [
                    'placeholder' => 'Note entre 1 à 5',
                    'min' => 1,
                    'max' => 5,
                    'step' => 1,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evaluation::class,
        ]);
    }
}
