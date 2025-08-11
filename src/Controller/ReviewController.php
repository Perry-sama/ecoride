<?php
namespace App\Controller;

use App\Entity\Review;
use App\Entity\Trajet;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/review')]
class ReviewController extends AbstractController
{
    #[Route('/new/{tripId}', name: 'review_new')]
    #[IsGranted('ROLE_USER')]
    public function new(int $tripId, Request $request, EntityManagerInterface $em): Response
    {
        $trip = $em->getRepository(Trajet::class)->find($tripId);
        if (!$trip) {
            throw $this->createNotFoundException('Trajet non trouvé.');
        }

        $review = new Review();
        $review->setTrip($trip);
        $review->setAuthor($this->getUser());
        $review->setDriver($trip->getUser()); // conducteur du trajet

        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $review->setStatus('pending'); // par défaut en attente
            $em->persist($review);
            $em->flush();

            $this->addFlash('success', 'Votre avis a été soumis et est en attente de validation.');

            return $this->redirectToRoute('trajet_show', ['id' => $tripId]);
        }

        return $this->render('review/new.html.twig', [
            'form' => $form->createView(),
            'trip' => $trip,
        ]);
    }

    #[Route('/moderate/{id}', name: 'review_moderate')]
    #[IsGranted('ROLE_EMPLOYEE')] // Seuls employés peuvent valider/refuser
    public function moderate(int $id, Request $request, ReviewRepository $repo, EntityManagerInterface $em): Response
    {
        $review = $repo->find($id);
        if (!$review) {
            throw $this->createNotFoundException('Avis non trouvé.');
        }

        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Avis mis à jour.');

            return $this->redirectToRoute('employee_reviews');
        }

        return $this->render('review/moderate.html.twig', [
            'form' => $form->createView(),
            'review' => $review,
        ]);
    }

    #[Route('/employee', name: 'employee_reviews')]
    #[IsGranted('ROLE_EMPLOYEE')]
    public function listForEmployee(ReviewRepository $repo): Response
    {
        $reviews = $repo->findBy(['status' => 'pending']);
        return $this->render('review/employee_list.html.twig', [
            'reviews' => $reviews,
        ]);
    }
}
