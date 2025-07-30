<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchRideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departure', TextType::class, [
                'label' => 'Ville de départ',
                'attr' => ['placeholder' => 'Ex : Paris']
            ])
            ->add('arrival', TextType::class, [
                'label' => 'Ville d’arrivée',
                'attr' => ['placeholder' => 'Ex : Lyon']
            ])
            ->add('date', DateType::class, [
                'label' => 'Date du trajet',
                'widget' => 'single_text'
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => ['class' => 'btn btn-success']
            ])
        ;
    }
}
