<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Matchs;
use App\Repository\MatchsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game')]
final class GameController extends AbstractController
{
    #[Route('/', name: 'app_game_index', methods: ['GET'])]
    public function index(MatchsRepository $matchsRepository): Response
    {
        $user = $this->getUser();
        $openMatches = $matchsRepository->findOpenMatches();

        return $this->render('game/open_matches.html.twig', [
            'openMatches' => $openMatches,
        ]);
    }

    #[Route('/create', name: 'app_game_create', methods: ['GET'])]
    public function create(EntityManagerInterface $entityManager): Response
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

        return $this->redirectToRoute('app_game_play', ['id' => $match->getId()]);
    }

    #[Route('/join/{id}', name: 'app_game_join', methods: ['GET'])]
    public function join(Matchs $match, EntityManagerInterface $entityManager): Response
    {
        $match->setOpen(false);

        // Obtener dos cartas aleatorias excluyendo las ya jugadas
        $cards = $entityManager->getRepository(Card::class)->findAll();
        $playedCards = $match->getRelationCards()->toArray();
        $availableCards = array_diff($cards, $playedCards);
        shuffle($availableCards);
        $match->addRelationCard($availableCards[0]);
        $match->addRelationCard($availableCards[1]);

        $entityManager->flush();

        return $this->redirectToRoute('app_game_play', ['id' => $match->getId()]);
    }

    #[Route('/play/{id}', name: 'app_game_play', methods: ['GET'])]
    public function play(Matchs $match): Response
    {
        return $this->render('game/index.html.twig', [
            'match' => $match,
            'cards' => $match->getRelationCards(),
        ]);
    }

    #[Route('/choose/{id}', name: 'app_game_choose', methods: ['GET'])]
    public function choose(Card $card, EntityManagerInterface $entityManager): Response
    {
        $match = $card->getRelationMatchs()->first();

        // Asignar la carta elegida al usuario actual
        if ($match->getRelationUser() === $this->getUser()) {
            $match->setUserCard($card);
        } else {
            $match->setOpponentCard($card);
        }

        // Verificar si ambos jugadores han elegido sus cartas
        if ($match->getUserCard() && $match->getOpponentCard()) {
            // Determinar el ganador
            $userCard = $match->getUserCard();
            $opponentCard = $match->getOpponentCard();
            $winner = null;
            if ($userCard->getValor() > $opponentCard->getValor()) {
                $winner = $match->getRelationUser();
            } elseif ($userCard->getValor() < $opponentCard->getValor()) {
                $winner = $match->getOpponent();
            }

            $match->setWinner($winner);
            $match->setOpen(false);

            $entityManager->flush();

            return $this->redirectToRoute('app_game_result', [
                'id' => $match->getId(),
                'userCard' => $userCard,
                'opponentCard' => $opponentCard,
            ]);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_game_wait', ['id' => $match->getId()]);
    }

    #[Route('/wait/{id}', name: 'app_game_wait', methods: ['GET'])]
    public function wait(Matchs $match): Response
    {
        return $this->render('game/wait.html.twig', [
            'match' => $match,
        ]);
    }

    #[Route('/result/{id}', name: 'app_game_result', methods: ['GET'])]
    public function result(Matchs $match): Response
    {
        return $this->render('game/result.html.twig', [
            'match' => $match,
            'winner' => $match->getWinner(),
            'userCard' => $match->getUserCard(),
            'opponentCard' => $match->getOpponentCard(),
        ]);
    }

    #[Route('/history', name: 'app_game_history', methods: ['GET'])]
    public function history(MatchsRepository $matchsRepository): Response
    {
        $user = $this->getUser(); // Obtiene el usuario actual
        $matchHistory = $matchsRepository->findUserMatchHistory($user); // Obtiene el historial de partidas

        return $this->render('game/history.html.twig', [
            'matchHistory' => $matchHistory,
        ]);
    }
}
