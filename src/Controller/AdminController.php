<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRoleType;
use App\Repository\TrajetRepository;
use App\Repository\UserRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[Route('/users/{id}/edit-roles', name: 'admin_user_edit_roles')]
    #[IsGranted('ROLE_ADMIN')]
    public function editRoles(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserRoleType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Rôles mis à jour avec succès.');
            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/users/edit_roles.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}

