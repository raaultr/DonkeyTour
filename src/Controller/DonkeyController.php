<?php

namespace App\Controller;

use App\Entity\Donkey;
use App\Form\DonkeyType;
use App\Repository\DonkeyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $donkey = new Donkey();
        $form = $this->createForm(DonkeyType::class, $donkey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
    public function edit(Request $request, Donkey $donkey, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonkeyType::class, $donkey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_donkey_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('donkey/edit.html.twig', [
            'donkey' => $donkey,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donkey_delete', methods: ['POST'])]
    public function delete(Request $request, Donkey $donkey, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donkey->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($donkey);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_donkey_index', [], Response::HTTP_SEE_OTHER);
    }
}
