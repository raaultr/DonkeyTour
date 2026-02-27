<?php

namespace App\Controller;

use App\Repository\DonkeyRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class ApiController extends AbstractController
{
    #[Route('/services', name: 'api_services', methods: ['GET'])]
    public function services(ServiceRepository $serviceRepository): JsonResponse
    {
        $services = $serviceRepository->findBy(['deletedAt' => null], ['basePrice' => 'ASC']);

        $data = [];
        foreach ($services as $service) {
            $item = [
                'id'          => $service->getId(),
                'type'        => $service->getType(),
                'basePrice'   => $service->getBasePrice(),
                'description' => $service->getDescription(),
                'duration'    => $service->getDuration()?->format('H:i'),
                'maxAphor'    => $service->getMaxAphor(),
                'leenguage'   => $service->getLeenguage(),
            ];

            // Campos específicos por tipo
            if ($service instanceof \App\Entity\Tour) {
                $item['name']             = $service->getName();
                $item['itinerary']        = $service->getItinerary();
                $item['stops']            = $service->getStops();
                $item['audioExplanation'] = $service->isAudioExplanation();
            }
            if ($service instanceof \App\Entity\Therapy) {
                $item['place'] = $service->getPlace();
            }
            if ($service instanceof \App\Entity\Despedida) {
                $item['tematica'] = $service->getTematica();
                $item['place']    = $service->getPlace();
            }

            $data[] = $item;
        }

        return $this->json($data);
    }

    #[Route('/donkeys', name: 'api_donkeys', methods: ['GET'])]
    public function donkeys(DonkeyRepository $donkeyRepository): JsonResponse
    {
        $donkeys = $donkeyRepository->findBy(['deletedAt' => null]);

        $data = [];
        foreach ($donkeys as $donkey) {
            $data[] = [
                'id'        => $donkey->getId(),
                'nombre'    => $donkey->getNombre(),
                'years'     => $donkey->getYears(),
                'race'      => $donkey->getRace(),
                'kilogram'  => $donkey->getKilogram(),
                'photoUrl'  => $donkey->getPhotoUrl(),
                'disponible'=> $donkey->isDisponible(),
            ];
        }

        return $this->json($data);
    }
}
