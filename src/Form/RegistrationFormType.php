<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('firstname', TextType::class, [
            'label' => 'Prénom'
            ])
        ->add('lastname', TextType::class, [
            'label' => 'Nom'
        ])
        ->add('email', EmailType::class, [
            'label' => 'Adresse email'
        ])
        ->add('plainPassword', PasswordType::class, [
            'label' => 'Mot de passe',
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank(['message' => 'Merci d’entrer un mot de passe']),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères',
                    'max' => 4096,
                ]),
            ],
        ])
    ;
}
}
