<?php

namespace App\Controller;

use App\Entity\Trajet;
use App\Form\SearchRideType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Formulaire de recherche (non actif pour le moment)
        $form = $this->createForm(SearchRideType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            dd($data); // Ici tu pourras faire un filtrage plus tard
        }

        // Récupération de tous les trajets pour les afficher sur la home
        $trajets = $entityManager->getRepository(Trajet::class)->findBy([], ['dateHeure' => 'ASC']);

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'trajets' => $trajets,
        ]);
    }
}
