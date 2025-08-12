<?php

namespace App\Controller;

use App\Entity\Trajet;
use App\Repository\TrajetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, TrajetRepository $trajetRepository): Response
    {
        $depart = $request->query->get('depart');
        $destination = $request->query->get('destination');

        $trajets = [];

        if ($depart || $destination) {
            $trajets = $trajetRepository->findBySearch($depart, $destination);
        }

        return $this->render('home/index.html.twig', [
            'trajets' => $trajets,
            'depart' => $depart,
            'destination' => $destination
        ]);
    }
}
