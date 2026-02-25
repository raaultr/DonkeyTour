<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client; 
use App\Entity\Employee;
use App\Entity\Admin;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
final class UserController extends AbstractController
{
    // Listado de empleados
    #[Route('/admin/employees', name: 'app_user_employees', methods: ['GET'])]
    public function employees(UserRepository $userRepository): Response
    {
        $allUsers = $userRepository->findAll();
        
        $employees = array_filter($allUsers, function($user) {
            $roles = $user->getRoles();
            return in_array('ROLE_EMPLOYEE', $roles);
        });

        return $this->render('user/index.html.twig', [
            'users' => $employees,
            'title' => 'Gestión de Empleados',
            'type' => 'employee',
        ]);
    }
    // Gestión de clientes
    #[Route('/admin/clients', name: 'app_user_clients', methods: ['GET'])]
    public function clients(UserRepository $userRepository): Response
    {
        $allUsers = $userRepository->findAll();
        
        $clients = array_filter($allUsers, function($user) {
            return in_array('ROLE_CLIENT', $user->getRoles());
        });

        return $this->render('user/index.html.twig', [
            'users' => $clients,
            'title' => 'Gestión de Clientes',
            'type' => 'client',
        ]);
    }
    
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'title' => 'Todos los Usuarios',
            'type' => 'user',
        ]);
    }

    #[Route('/new/{type}', name: 'app_user_new', methods: ['GET', 'POST'], defaults: ['type' => 'user'])]
    public function new(
        Request $request, 
        string $type, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = match($type) {
            'employee' => new Employee(),
            'client'   => new Client(),
            'admin'    => new Admin(),
            default    => new User(),
        };
        
        // 2. Configuramos el label para la vista
        $label = match($type) {
            'employee' => 'Empleado',
            'client'   => 'Cliente',
            default    => 'Usuario',
        };

        if ($type === 'employee') $user->setRoles(['ROLE_EMPLOYEE']);
        if ($type === 'client') $user->setRoles(['ROLE_CLIENT']);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Cifrar contraseña
            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($hashedPassword);
            $user->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "¡$label creado correctamente!");

            // Redirigir a la tabla correspondiente
            $route = match($type) {
                'employee' => 'app_user_employees',
                'client'   => 'app_user_clients',
                default    => 'app_user_index'
            };
            return $this->redirectToRoute($route);
        }

        return $this->render('user/new.html.twig', [
            'form' => $form,
            'type' => $label,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
