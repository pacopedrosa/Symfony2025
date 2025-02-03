<?php

namespace App\Controller;

use App\Entity\Hall;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\MessageType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity as AttributeMapEntity;
use Symfony\Component\Routing\Attribute\MapEntity;

final class MessageController extends AbstractController
{
    #[Route('/message/{hallId}', name: 'app_message')]
    public function index(Request $request, EntityManagerInterface $entityManager,#[AttributeMapEntity(id: 'hallId')] Hall $hall): Response
    {
        if ($hall->getStatus() === 'inactive') {
            $this->addFlash('error', 'Esta sala está inactiva y no se puede acceder.');
            return $this->redirectToRoute('app_hall_index');
        }

        $messages = $entityManager->getRepository(Message::class)->findByHallId($hall->getId());
        $users = $entityManager->getRepository(User::class)->findAll();

        $message = new Message();
        $message->setIdUser($this->getUser());
        $message->setHall($hall);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Mensaje enviado correctamente');
            return $this->redirectToRoute('app_message', ['hallId' => $hall->getId()]);
        }
        return $this->render('message/index.html.twig', [
            'form' => $form->createView(),            
            'messages' => $messages,
            'users' => $users,
            'hall' => $hall,
        ]);
    }
    #[Route('/message/{hallId}/leave', name: 'app_message_leave')] 
    public function leaveHall(EntityManagerInterface $entityManager, #[AttributeMapEntity(id: 'hallId')] Hall $hall): Response
    {
        // Obtener todos los mensajes de la sala
        $messages = $entityManager->getRepository(Message::class)->findByHallId($hall->getId());
        
        // Obtener usuarios únicos que han enviado mensajes
        $usersInChat = [];
        foreach ($messages as $message) {
            $userId = $message->getIdUser();
            if (!in_array($userId, $usersInChat, true)) {
                $usersInChat[] = $userId;
            }
        }
        
        // Remover el usuario actual de la lista
        $otherUsers = array_filter($usersInChat, function($user) {
            return $user !== $this->getUser();
        });
        
        // Si no quedan otros usuarios, marcar la sala como inactiva
        if (empty($otherUsers)) {
            $hall->setStatus('inactive');
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_hall_index');
    }
}
