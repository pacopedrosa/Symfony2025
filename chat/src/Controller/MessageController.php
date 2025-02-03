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

        // Registrar que el usuario está en esta sala
        $entityManager->getRepository(User::class)
            ->updateUserCurrentHall($this->getUser(), $hall);



        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $message->setIdUser($this->getUser());
            $message->setHall($hall);
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Mensaje enviado correctamente');
            return $this->redirectToRoute('app_message', ['hallId' => $hall->getId()]);
        }
        return $this->render('message/index.html.twig', [
            'form' => $form->createView(),            
            'hall' => $hall,
        ]);
    }
    #[Route('/message/{id}/leave', name: 'app_message_leave')] 
    public function leaveHall(EntityManagerInterface $entityManager, Hall $hall): Response
    {
        // Actualizar el usuario actual (removerlo de la sala)        
        $this->getUser()->setCurrentHall(null);
        $entityManager->flush();
        
        // Verificar si quedan usuarios activos en la sala
        $activeUsers = $entityManager->getRepository(User::class)
            ->findActiveUsersInHall($hall, $this->getUser());

        if (empty($activeUsers)) {
            $hall->setStatus('inactive');
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_hall_index');
    }
}

