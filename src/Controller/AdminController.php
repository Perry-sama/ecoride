<?php

namespace App\Controller;

use App\Repository\TrajetRepository;
use App\Repository\UserRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_index')]
    public function index(
        UserRepository $userRepo,
        TrajetRepository $trajetRepo,
        ReservationRepository $reservationRepo
    ): Response {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepo->findAll(),
            'trajets' => $trajetRepo->findAll(),
            'reservations' => $reservationRepo->findAll(),
        ]);
    }
}
