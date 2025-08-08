<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRoleType;
use App\Form\EmployeeType;
use App\Repository\TrajetRepository;
use App\Repository\UserRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_index')]
    public function index(
        UserRepository $userRepo,
        TrajetRepository $trajetRepo,
        ReservationRepository $reservationRepo
    ): Response {
        $reservations = $reservationRepo->findAllWithUserAndTrajet();

        return $this->render('admin/index.html.twig', [
            'users' => $userRepo->findAll(),
            'trajets' => $trajetRepo->findAll(),
            'reservations' => $reservations,
        ]);
    }

    #[Route('/users/{id}/edit-roles', name: 'admin_user_edit_roles')]
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

    #[Route('/users/{id}/toggle-active', name: 'admin_user_toggle_active', methods: ['POST'])]
    public function toggleActive(User $user, EntityManagerInterface $em): Response
    {
        $user->setIsActive(!$user->getIsActive());
        $em->flush();

        $this->addFlash('success', sprintf(
            'Le compte de %s est maintenant %s.',
            $user->getEmail(),
            $user->getIsActive() ? 'activé' : 'suspendu'
        ));

        return $this->redirectToRoute('app_admin_index');
    }

    #[Route('/reservations', name: 'admin_reservations_index')]
    public function reservations(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findAllWithUserAndTrajet();

        return $this->render('admin/reservations/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $conn = $em->getConnection();

        $sqlTotal = "SELECT SUM(t.prix * r.places) as totalCredits
                     FROM reservation r
                     INNER JOIN trajet t ON r.trajet_id = t.id";
        $totalCredits = $conn->executeQuery($sqlTotal)->fetchOne() ?? 0;

        $sqlCount = "SELECT DATE(r.created_at) as day, COUNT(r.id) as count
                     FROM reservation r
                     GROUP BY day ORDER BY day";
        $covoituragesPerDay = $conn->executeQuery($sqlCount)->fetchAllAssociative();

        $sqlGain = "SELECT DATE(r.created_at) as day, SUM(t.prix * r.places) as gain
                    FROM reservation r
                    INNER JOIN trajet t ON r.trajet_id = t.id
                    GROUP BY day ORDER BY day";
        $gainPerDay = $conn->executeQuery($sqlGain)->fetchAllAssociative();

        return $this->render('admin/dashboard.html.twig', [
            'totalCredits' => $totalCredits,
            'covoituragesPerDay' => $covoituragesPerDay,
            'gainPerDay' => $gainPerDay,
        ]);
    }

    #[Route('/employee/new', name: 'admin_employee_new')]
    public function newEmployee(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(EmployeeType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_EMPLOYEE']);
            $user->setIsActive(true);
            $user->setIsVerified(true);

            $plainPassword = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Employé créé avec succès.');

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/employee/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
