<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\MessageType;

final class MessageController extends AbstractController
{
    #[Route('/message', name: 'app_message')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $messages = $entityManager->getRepository(Message::class)->findAll();
        $users = $entityManager->getRepository(User::class)->findAll();

        $message = new Message();
        $message->setIdUser($this->getUser());
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Mensaje enviado correctamente');
            return $this->redirectToRoute('app_message');
        }
        return $this->render('message/index.html.twig', [
            'form' => $form->createView(),
            'messages' => $messages,
            'users' => $users,
        ]);
    }
}
