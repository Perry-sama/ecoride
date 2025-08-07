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
    #[Route('/profil', name: 'app_user_profil')]
    public function profil(
        TrajetRepository $trajetRepo,
        ReservationRepository $reservationRepo,
        Security $security
    ): Response {
        $user = $security->getUser();

        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à votre profil.');
            return $this->redirectToRoute('app_login');
        }

        $trajetsCrees = $trajetRepo->findBy(['user' => $user]);
        $reservations = $reservationRepo->findBy(['user' => $user]);

        return $this->render('user/mon_profil.html.twig', [
            'trajets_crees' => $trajetsCrees,
            'trajets_reserves' => $reservations,
        ]);
    }
}
