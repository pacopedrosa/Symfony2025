<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/usuarios', name: 'admin_users')]
    public function userList(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $users = $userRepository->findAll();
        
        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/usuario/{id}/cambiar-rol', name: 'admin_change_role', methods: ['POST'])]
    public function changeUserRole(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $newRole = $request->request->get('role');
        $allowedRoles = [User::ROLE_USER, User::ROLE_TRABAJADOR, User::ROLE_ADMIN];
        
        if (in_array($newRole, $allowedRoles)) {
            $user->setRoles([$newRole]);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('admin_users');
    }
} 