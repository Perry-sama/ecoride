<?php
// src/Form/EmployeeType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('firstname', TextType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'constraints' => [new NotBlank()],
                'help' => 'Mot de passe temporaire à changer à la première connexion',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
