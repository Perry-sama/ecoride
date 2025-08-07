<?php

namespace App\Controller;

use App\Repository\TrajetRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserRoleType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserController extends AbstractController
{
   #[Route('/mon-profil', name: 'app_profil')]
public function profil(ReservationRepository $reservationRepository): Response
{
    /** @var User $user */
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    // Récupération des réservations de l'utilisateur connecté
    $reservations = $reservationRepository->findBy(['user' => $user]);

    return $this->render('user/profil.html.twig', [
        'user' => $user,
        'reservations' => $reservations,
    ]);
}
}
