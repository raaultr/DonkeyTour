<?php

namespace App\Controller;

use App\Entity\Reserve;
use App\Repository\ReserveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/reservas')]
final class AdminReserveController extends AbstractController
{
    /**
     * Listado global de TODAS las reservas (cualquier fecha, cualquier usuario).
     */
    #[Route('', name: 'app_admin_reserve_index', methods: ['GET'])]
    public function index(ReserveRepository $reserveRepository): Response
    {
        $reserves = $reserveRepository->findBy([], ['reserveDate' => 'DESC']);

        return $this->render('admin/reserve/index.html.twig', [
            'reserves' => $reserves,
        ]);
    }

    /**
     * Detalle completo de una reserva.
     */
    #[Route('/{id}', name: 'app_admin_reserve_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Reserve $reserve): Response
    {
        $details = json_decode($reserve->getDetails() ?? '{}', true);

        return $this->render('admin/reserve/show.html.twig', [
            'reserve' => $reserve,
            'details' => $details,
        ]);
    }

    /**
     * Cancelar / cambiar estado de una reserva.
     */
    #[Route('/{id}/toggle-state', name: 'app_admin_reserve_toggle', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function toggleState(Request $request, Reserve $reserve, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('toggle' . $reserve->getId(), $request->request->get('_token'))) {
            $reserve->setState(!$reserve->isState());
            $reserve->setUpdatedAt(new \DateTime());
            $em->flush();

            $this->addFlash('success', 'Estado de la reserva actualizado.');
        }

        return $this->redirectToRoute('app_admin_reserve_show', ['id' => $reserve->getId()]);
    }
}
