<?php

namespace App\Controller;

use App\Entity\Matchs;
use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $openMatches = $entityManager->getRepository(Matchs::class)->findBy(['open' => true]);

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'openMatches' => $openMatches,
        ]);
    }

    #[Route('/create-match', name: 'app_create_match')]
    public function createMatch(EntityManagerInterface $entityManager): Response
    {
        $match = new Matchs();
        $match->setOpen(true);
        $match->setRelationUser($this->getUser());

        // Obtener dos cartas aleatorias
        $cards = $entityManager->getRepository(Card::class)->findAll();
        shuffle($cards);
        $match->addRelationCard($cards[0]);
        $match->addRelationCard($cards[1]);

        $entityManager->persist($match);
        $entityManager->flush();

        return $this->redirectToRoute('app_default');
    }
}
