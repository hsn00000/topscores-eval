<?php

namespace App\Form;

use App\Entity\Jeu;
use App\Entity\Stream;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StreamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateStart', null, [
                'widget' => 'single_text',
            ])
            ->add('url')
            ->add('jeu', EntityType::class, [
                'class' => Jeu::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stream::class,
        ]);
    }
}
