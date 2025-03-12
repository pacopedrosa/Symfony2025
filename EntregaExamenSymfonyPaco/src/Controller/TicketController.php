<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Entity\Messages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TicketController extends AbstractController
{
    #[Route('/ticket', name: 'app_ticket')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tickets = $entityManager->getRepository(Tickets::class)->findAll();
        return $this->render('ticket/index.html.twig', [
            'tickets' => $tickets,
            'controller_name' => 'TicketController',
        ]);
    }

    #[Route('/ticket/new', name: 'app_ticket_new')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Verificar que solo usuarios con ROLE_USER puedan crear tickets
        if (!$this->isGranted('ROLE_USER') || $this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_TRABAJADOR')) {
            throw $this->createAccessDeniedException('No tienes permiso para crear tickets.');
        }

        if($request->isMethod('POST')){
            $title = $request->request->get('title');
            $message = $request->request->get('message');

            $ticket = new Tickets();
            $ticket->setTitle($title);
            $ticket->setMessage($message);
            $ticket->setCliente($this->getUser());
            $ticket->setState(0); // Estado inicial del ticket

            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('app_ticket_user');
        }
        
        return $this->render('ticket/new.html.twig', [
            'controller_name' => 'TicketController',
        ]);
    }

    #[Route('/ticket/all', name: 'app_ticket_all')]
    public function allTickets(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $tickets = $entityManager->getRepository(Tickets::class)->findAll();
        return $this->render('ticket/all.html.twig', [
            'tickets' => $tickets,
            'controller_name' => 'TicketController',
        ]);
    }

    #[Route('/ticket/user', name: 'app_ticket_user')]
    public function ticketsUser(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $tickets = $entityManager->getRepository(Tickets::class)->findBy(['cliente' => $this->getUser()]);
        return $this->render('ticket/ticketsUser.html.twig', [
            'tickets' => $tickets,
            'controller_name' => 'TicketController',
        ]);
    }

    #[Route('/ticket/{id}/response', name: 'app_ticket_response')]
    public function response(Tickets $ticket, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Verificar que sea el trabajador asignado o el cliente del ticket
        if (!($this->isGranted('ROLE_TRABAJADOR') || ($this->isGranted('ROLE_USER') && $ticket->getCliente() === $this->getUser()))) {
            throw $this->createAccessDeniedException('No tienes permiso para responder a este ticket.');
        }

        if ($request->isMethod('POST')) {
            $content = $request->request->get('content');

            $message = new Messages();
            $message->setContent($content);
            $message->setTicket($ticket);
            $message->setAuthor($this->getUser());
            
            // Solo asignar trabajador si es un trabajador respondiendo
            if ($this->isGranted('ROLE_TRABAJADOR') && !$ticket->getOwner()) {
                $ticket->setOwner($this->getUser());
                $ticket->setState(1);
            }

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->isGranted('ROLE_TRABAJADOR') 
                ? $this->redirectToRoute('app_ticket')
                : $this->redirectToRoute('app_ticket_user');
        }

        return $this->render('ticket/response.html.twig', [
            'ticket' => $ticket
        ]);
    }

    #[Route('/ticket/{id}/close', name: 'app_ticket_close', methods: ['POST'])]
    public function closeTicket(Tickets $ticket, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TRABAJADOR');
        
        $ticket->setState(2); // Estado cerrado
        $entityManager->flush();
        
        return $this->redirectToRoute('app_ticket');
    }
}
