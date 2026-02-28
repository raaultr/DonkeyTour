<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SponsorshipController extends AbstractController
{
    #[Route('/sponsorship', name: 'app_sponsorship')]
    public function index(): Response
    {
        return $this->render('sponsorship/index.html.twig', [
            'controller_name' => 'SponsorshipController',
        ]);
    }
}
