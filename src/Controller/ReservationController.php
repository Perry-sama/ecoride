<?php

namespace App\Controller;

use App\Entity\Trajet;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/trajet/{id}', name: 'app_reservation_trajet')]
    #[IsGranted('ROLE_USER')]
    public function reserver(Trajet $trajet, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Vérifie si le trajet a encore des places
        if ($trajet->getNbPlaces() <= 0) {
            $this->addFlash('danger', 'Ce trajet est complet.');
            return $this->redirectToRoute('trajet_index');
        }

        // Vérifie si l'utilisateur a déjà réservé ce trajet
        $existing = $em->getRepository(Reservation::class)->findOneBy([
            'trajet' => $trajet,
            'user' => $user,
        ]);

        if ($existing) {
            $this->addFlash('warning', 'Vous avez déjà réservé ce trajet.');
            return $this->redirectToRoute('trajet_index');
        }

        // Crée la réservation
        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setTrajet($trajet);
        $reservation->setPlaces(1); // Par défaut 1 place réservée

        // Met à jour les places restantes
        $trajet->setNbPlaces($trajet->getNbPlaces() - 1);

        $em->persist($reservation);
        $em->flush();

        $this->addFlash('success', 'Réservation effectuée avec succès.');
        return $this->redirectToRoute('trajet_index');
    }
}
