<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProfileType;
use App\Form\ChangePasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ProfileController extends AbstractController
{
    #[Route('/mon-profil/modifier', name: 'app_profile_edit')]
public function edit(Request $request, EntityManagerInterface $em): Response
{
    $user = $this->getUser();
    $form = $this->createForm(ProfileType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        $this->addFlash('success', 'Profil mis à jour avec succès !');
        return $this->redirectToRoute('app_profile');
    }

    return $this->render('front/profile/edit.html.twig', [
        'profileForm' => $form->createView(),
    ]);
}

#[Route('/mon-profil/mot-de-passe', name: 'app_profile_password')]
public function changePassword(
    Request $request,
    UserPasswordHasherInterface $passwordHasher,
    EntityManagerInterface $em
): Response {
    $user = $this->getUser();
    $form = $this->createForm(ChangePasswordType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $plainPassword = $form->get('plainPassword')->getData();
        /** @var \App\Entity\User $user */
        $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $em->flush();
        $this->addFlash('success', 'Mot de passe modifié avec succès.');

        return $this->redirectToRoute('app_profile');
    }

    return $this->render('front/profile/password.html.twig', [
        'passwordForm' => $form->createView(),
    ]);
}
}
