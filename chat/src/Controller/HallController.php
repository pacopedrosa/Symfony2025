<?php

namespace App\Controller;

use App\Entity\Hall;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/hall')]
final class HallController extends AbstractController
{
    #[Route('/', name: 'app_hall_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $halls = $entityManager->getRepository(Hall::class)->findAll();

        return $this->render('hall/index.html.twig', [
            'halls' => $halls,
        ]);
    }

    #[Route('/new', name: 'app_hall_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $hall = new Hall();
        $hall->setUser($this->getUser());
        $hall->setStatus('active');
        
        $entityManager->persist($hall);
        $entityManager->flush();

        return $this->redirectToRoute('app_hall_index');
    }
}
