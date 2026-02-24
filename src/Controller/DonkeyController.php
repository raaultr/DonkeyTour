<?php

namespace App\Controller;

use App\Entity\Donkey;
use App\Form\DonkeyType;
use App\Repository\DonkeyRepository;
use App\Repository\DonkeyReserveRepository; // Añadimos esto
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/donkey')]
final class DonkeyController extends AbstractController
{
    #[Route(name: 'app_donkey_index', methods: ['GET'])]
    public function index(DonkeyRepository $donkeyRepository): Response
    {
        return $this->render('donkey/index.html.twig', [
            'donkeys' => $donkeyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_donkey_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager, DonkeyReserveRepository $reserveRepo): Response
    {
        $donkey = new Donkey();
        $form = $this->createForm(DonkeyType::class, $donkey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sincronización de fechas con los tipos de la entidad
            $donkey->setCreatedAt(new \DateTimeImmutable());
            $donkey->setUpdatedAt(new \DateTime());
            $donkey->setDeletedAt(null);

            $entityManager->persist($donkey);
            $entityManager->flush();

            return $this->redirectToRoute('app_donkey_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('donkey/new.html.twig', [
            'donkey' => $donkey,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donkey_show', methods: ['GET'])]
    public function show(Donkey $donkey): Response
    {
        return $this->render('donkey/show.html.twig', [
            'donkey' => $donkey,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_donkey_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Donkey $donkey, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonkeyType::class, $donkey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donkey->setUpdatedAt(new \DateTime());
            $entityManager->flush();

            return $this->redirectToRoute('app_donkey_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('donkey/edit.html.twig', [
            'donkey' => $donkey,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donkey_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Donkey $donkey, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donkey->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($donkey);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_donkey_index', [], Response::HTTP_SEE_OTHER);
    }
}