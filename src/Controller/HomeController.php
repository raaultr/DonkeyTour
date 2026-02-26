<?php

namespace App\Controller;

use App\Repository\DonkeyRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(DonkeyRepository $donkeyRepository, ServiceRepository $serviceRepository): Response
    {
        $donkeys = $donkeyRepository->findAllAvailable();
        $services = $serviceRepository->findBy(['deletedAt' => null], ['basePrice' => 'ASC'], 6);

        return $this->render('home/index.html.twig', [
            'donkeys' => $donkeys,
            'services' => $services,
        ]);
    }

    #[Route('/sobre-nosotros', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }
}
