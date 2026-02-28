<?php

namespace App\Controller;

use App\Repository\DonkeyRepository;
use App\Repository\ServiceRepository;
use App\Repository\ReserveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(
        DonkeyRepository $donkeyRepository, 
        ServiceRepository $serviceRepository,
        ReserveRepository $reserveRepository
    ): Response {
        $donkeys = $donkeyRepository->findAllAvailable();
        $services = $serviceRepository->findBy(['deletedAt' => null], ['basePrice' => 'ASC'], 6);
        
        // Buscamos todas las reservas para las estadísticas del admin
        $reserves = $reserveRepository->findAll();

        return $this->render('home/index.html.twig', [
            'donkeys' => $donkeyRepository->findAll(),
            'services' => $serviceRepository->findAll(), 
            'reserves' => $reserveRepository->findAll(),
        ]);
    }

    #[Route('/sobre-nosotros', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }
}
