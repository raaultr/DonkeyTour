<?php

namespace App\Controller;

use App\Entity\Reserve;
use App\Entity\ClientReserve;
use App\Entity\DonkeyReserve;
use App\Entity\Donkey;
use App\Entity\Service;
use App\Repository\ReserveRepository;
use App\Repository\ServiceRepository;
use App\Repository\DonkeyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reserve')]
final class ReserveController extends AbstractController
{
    /**
     * Mis reservas — listado del usuario autenticado.
     */
    #[Route('/my-reserves', name: 'app_reserve_my', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function myReserves(ReserveRepository $reserveRepository): Response
    {
        $reserves = $reserveRepository->findBy(
            ['bookedBy' => $this->getUser(), 'deletedAt' => null],
            ['reserveDate' => 'DESC']
        );

        return $this->render('reserve/my_reserves.html.twig', [
            'reserves' => $reserves,
        ]);
    }

    /* ======================================================
     *  WIZARD — PASO 1: Selección de servicio
     * ====================================================== */

    #[Route('/new', name: 'app_reserve_step1', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function step1(ServiceRepository $serviceRepository, Request $request): Response
    {
        // Reiniciar datos del wizard
        $request->getSession()->remove('reserve_wizard');

        return $this->render('reserve/seleccionar_servicio.html.twig', [
            'services'     => $serviceRepository->findBy(['deletedAt' => null]),
            'current_step' => 1,
        ]);
    }

    #[Route('/new/select-service', name: 'app_reserve_step1_post', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function step1Post(Request $request): Response
    {
        $serviceId = $request->request->getInt('service_id');
        if (!$serviceId) {
            $this->addFlash('error', 'Por favor, selecciona un servicio.');
            return $this->redirectToRoute('app_reserve_step1');
        }

        $request->getSession()->set('reserve_wizard', ['service_id' => $serviceId]);

        return $this->redirectToRoute('app_reserve_step2');
    }

    /* ======================================================
     *  WIZARD — PASO 2: Fecha y hora
     * ====================================================== */

    #[Route('/new/datetime', name: 'app_reserve_step2', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function step2(Request $request, EntityManagerInterface $em): Response
    {
        $wizard = $request->getSession()->get('reserve_wizard');
        if (!$wizard || !isset($wizard['service_id'])) {
            return $this->redirectToRoute('app_reserve_step1');
        }

        $service = $em->getRepository(Service::class)->find($wizard['service_id']);
        if (!$service) {
            return $this->redirectToRoute('app_reserve_step1');
        }

        return $this->render('reserve/fecha_hora.html.twig', [
            'service'      => $service,
            'current_step' => 2,
        ]);
    }

    #[Route('/new/select-datetime', name: 'app_reserve_step2_post', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function step2Post(Request $request): Response
    {
        $wizard = $request->getSession()->get('reserve_wizard');
        if (!$wizard) {
            return $this->redirectToRoute('app_reserve_step1');
        }

        $datetime = $request->request->get('datetime');
        if (!$datetime) {
            $this->addFlash('error', 'Por favor, selecciona una fecha y hora.');
            return $this->redirectToRoute('app_reserve_step2');
        }

        // Validar que la fecha es futura
        $selectedDate = new \DateTime($datetime);
        if ($selectedDate <= new \DateTime()) {
            $this->addFlash('error', 'La fecha debe ser posterior a hoy.');
            return $this->redirectToRoute('app_reserve_step2');
        }

        $wizard['datetime'] = $datetime;
        $request->getSession()->set('reserve_wizard', $wizard);

        return $this->redirectToRoute('app_reserve_step3');
    }

    /* ======================================================
     *  WIZARD — PASO 3: Selección de burro
     * ====================================================== */

    #[Route('/new/donkey', name: 'app_reserve_step3', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function step3(Request $request, DonkeyRepository $donkeyRepository): Response
    {
        $wizard = $request->getSession()->get('reserve_wizard');
        if (!$wizard || !isset($wizard['datetime'])) {
            return $this->redirectToRoute('app_reserve_step2');
        }

        $date = new \DateTime($wizard['datetime']);

        return $this->render('reserve/seleccionar_burro.html.twig', [
            'donkeys'      => $donkeyRepository->findAvailableForDate($date),
            'current_step' => 3,
        ]);
    }

    #[Route('/new/select-donkey', name: 'app_reserve_step3_post', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function step3Post(Request $request): Response
    {
        $wizard = $request->getSession()->get('reserve_wizard');
        if (!$wizard) {
            return $this->redirectToRoute('app_reserve_step1');
        }

        $donkeyId = $request->request->getInt('donkey_id');
        if (!$donkeyId) {
            $this->addFlash('error', 'Por favor, selecciona un burro.');
            return $this->redirectToRoute('app_reserve_step3');
        }

        $wizard['donkey_id'] = $donkeyId;
        $request->getSession()->set('reserve_wizard', $wizard);

        return $this->redirectToRoute('app_reserve_step4');
    }

    /* ======================================================
     *  WIZARD — PASO 4: Datos de acompañantes
     * ====================================================== */

    #[Route('/new/companions', name: 'app_reserve_step4', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function step4(Request $request, EntityManagerInterface $em): Response
    {
        $wizard = $request->getSession()->get('reserve_wizard');
        if (!$wizard || !isset($wizard['donkey_id'])) {
            return $this->redirectToRoute('app_reserve_step3');
        }

        $service = $em->getRepository(Service::class)->find($wizard['service_id']);

        return $this->render('reserve/acompanantes.html.twig', [
            'service'        => $service,
            'max_companions' => max(0, ($service ? $service->getMaxAphor() : 1) - 1),
            'current_step'   => 4,
        ]);
    }

    #[Route('/new/save-companions', name: 'app_reserve_step4_post', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function step4Post(Request $request): Response
    {
        $wizard = $request->getSession()->get('reserve_wizard');
        if (!$wizard) {
            return $this->redirectToRoute('app_reserve_step1');
        }

        $companions = $request->request->all('companions');
        $filtered   = [];

        if (is_array($companions)) {
            foreach ($companions as $c) {
                if (!empty($c['nombre'])) {
                    $filtered[] = [
                        'nombre'   => $c['nombre'],
                        'nif'      => $c['nif'] ?? '',
                        'telefono' => $c['telefono'] ?? '',
                    ];
                }
            }
        }

        $wizard['companions'] = $filtered;
        $request->getSession()->set('reserve_wizard', $wizard);

        return $this->redirectToRoute('app_reserve_step5');
    }

    /* ======================================================
     *  WIZARD — PASO 5: Confirmación
     * ====================================================== */

    #[Route('/new/confirm', name: 'app_reserve_step5', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function step5(Request $request, EntityManagerInterface $em): Response
    {
        $wizard = $request->getSession()->get('reserve_wizard');
        if (!$wizard || !isset($wizard['service_id'])) {
            return $this->redirectToRoute('app_reserve_step1');
        }

        $service = $em->getRepository(Service::class)->find($wizard['service_id']);
        $donkey  = $em->getRepository(Donkey::class)->find($wizard['donkey_id']);

        return $this->render('reserve/confirmacion.html.twig', [
            'service'      => $service,
            'donkey'       => $donkey,
            'datetime'     => $wizard['datetime'],
            'companions'   => $wizard['companions'] ?? [],
            'user'         => $this->getUser(),
            'current_step' => 5,
        ]);
    }

    /**
     * Crear la reserva definitiva.
     */
    #[Route('/new/finalize', name: 'app_reserve_finalize', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function finalize(Request $request, EntityManagerInterface $em): Response
    {
        $wizard = $request->getSession()->get('reserve_wizard');
        if (!$wizard || !isset($wizard['service_id'])) {
            return $this->redirectToRoute('app_reserve_step1');
        }

        if (!$this->isCsrfTokenValid('reserve_finalize', $request->request->get('_token'))) {
            $this->addFlash('error', 'Token de seguridad inválido. Inténtalo de nuevo.');
            return $this->redirectToRoute('app_reserve_step5');
        }

        $service = $em->getRepository(Service::class)->find($wizard['service_id']);
        $donkey  = $em->getRepository(Donkey::class)->find($wizard['donkey_id']);
        $user    = $this->getUser();

        if (!$service || !$donkey) {
            $this->addFlash('error', 'Datos inválidos. Empieza de nuevo.');
            return $this->redirectToRoute('app_reserve_step1');
        }

        // --- Crear DonkeyReserve ---
        $donkeyReserve = new DonkeyReserve();
        $donkeyReserve->addDonkey($donkey);
        $em->persist($donkeyReserve);

        // --- Crear ClientReserve para quien reserva ---
        $clientReserve = new ClientReserve();
        $clientReserve->setAsist(true);
        $clientReserve->setReserveWho(true);
        if ($user instanceof \App\Entity\Client) {
            $clientReserve->setClientAssist($user);
        }
        $em->persist($clientReserve);

        // --- Crear Reserve ---
        $reserve = new Reserve();
        $reserve->setReserveDate(new \DateTime($wizard['datetime']));
        $reserve->setState(true);
        $reserve->setService($service);
        $reserve->setDonkeyReserve($donkeyReserve);
        $reserve->setClientReserve($clientReserve);
        $reserve->setBookedBy($user);
        $reserve->setSelectedDonkey($donkey);
        $reserve->setCreatedAt(new \DateTimeImmutable());

        // Guardar acompañantes + datos del reservante como JSON
        $companions = $wizard['companions'] ?? [];
        $reserve->setDetails(json_encode([
            'companions' => $companions,
            'booker'     => [
                'nombre'   => $user->getNombre(),
                'email'    => $user->getEmail(),
                'nif'      => $user->getNif(),
                'telefono' => $user->getTelefono(),
            ],
        ], JSON_UNESCAPED_UNICODE));

        $em->persist($reserve);
        $em->flush();

        // Limpiar wizard
        $request->getSession()->remove('reserve_wizard');

        $this->addFlash('success', '¡Tu reserva se ha creado correctamente!');

        return $this->redirectToRoute('app_reserve_success', ['id' => $reserve->getId()]);
    }

    /* ======================================================
     *  Página de éxito
     * ====================================================== */

    #[Route('/success/{id}', name: 'app_reserve_success', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function success(Reserve $reserve): Response
    {
        return $this->render('reserve/success.html.twig', [
            'reserve' => $reserve,
        ]);
    }

    /* ======================================================
     *  Detalle de reserva (usuario)
     * ====================================================== */

    #[Route('/{id}', name: 'app_reserve_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function show(Reserve $reserve): Response
    {
        $details = json_decode($reserve->getDetails() ?? '{}', true);

        return $this->render('reserve/show.html.twig', [
            'reserve' => $reserve,
            'details' => $details,
        ]);
    }
}
