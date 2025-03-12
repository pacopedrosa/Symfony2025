<?php

namespace App\Controller;

use App\Entity\Assessment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Tickets;

final class AssessmentController extends AbstractController
{
    #[Route('/assessment/{id}/new', name: 'app_assessment_new')]
    public function newAssessment(Tickets $ticket, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        if ($ticket->getCliente() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No puedes valorar un ticket que no es tuyo.');
        }

        if ($request->isMethod('POST')) {
            $stars = $request->request->get('stars');
            if ($stars >= 1 && $stars <= 5) {
                $assessment = new Assessment();
                $assessment->setStars($stars);
                $entityManager->persist($assessment);
                
                $ticket->setAssessment($assessment);
                $entityManager->flush();
                
                return $this->redirectToRoute('app_ticket_user');
            }
        }

        return $this->render('assessment/new.html.twig', [
            'ticket' => $ticket,
        ]);
    }
}
