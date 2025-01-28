<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Card;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class GameService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Genera un conjunto de cartas aleatorias para la partida
     * - Crea todas las cartas posibles (1-10 de cada palo)
     * - Las mezcla y devuelve la cantidad solicitada
     */
    public function getRandomCards(int $count): array
    {
        $cards = [];
        $numbers = range(1, 10);
        $suits = ['hearts', 'diamonds', 'clubs', 'spades'];

        // Crea el mazo completo de cartas
        foreach ($suits as $suit) {
            foreach ($numbers as $number) {
                $card = new Card();
                $card->setNumber($number);
                $card->setSuit($suit);
                $this->entityManager->persist($card);
                $cards[] = $card;
            }
        }
        
        $this->entityManager->flush();
        
        // Mezcla y devuelve las cartas solicitadas
        shuffle($cards);
        return array_slice($cards, 0, $count);
    }

    /**
     * Determina el ganador de la partida según las reglas:
     * 1. Gana la pareja más alta
     * 2. Si solo uno tiene pareja, ese gana
     * 3. Si nadie tiene pareja, gana la carta más alta
     */
    public function determineWinner(Game $game): ?User
    {
        $player1Cards = [$game->getPlayer1Card1(), $game->getPlayer1Card2()];
        $player2Cards = [$game->getPlayer2Card1(), $game->getPlayer2Card2()];

        // Comprueba si hay parejas
        $player1Pair = $this->hasPair($player1Cards);
        $player2Pair = $this->hasPair($player2Cards);

        // Si ambos tienen pareja, gana la más alta
        if ($player1Pair && $player2Pair) {
            if ($player1Pair > $player2Pair) {
                return $game->getPlayer1();
            } elseif ($player2Pair > $player1Pair) {
                return $game->getPlayer2();
            }
            return null; // Empate si las parejas son iguales
        }

        // Si solo uno tiene pareja, ese gana
        if ($player1Pair) return $game->getPlayer1();
        if ($player2Pair) return $game->getPlayer2();

        // Si nadie tiene pareja, gana la carta más alta
        $player1Highest = max($player1Cards[0]->getNumber(), $player1Cards[1]->getNumber());
        $player2Highest = max($player2Cards[0]->getNumber(), $player2Cards[1]->getNumber());

        if ($player1Highest > $player2Highest) {
            return $game->getPlayer1();
        } elseif ($player2Highest > $player1Highest) {
            return $game->getPlayer2();
        }

        return null; // Empate si las cartas más altas son iguales
    }

    /**
     * Comprueba si hay una pareja entre las cartas
     * Devuelve el número de la pareja o null si no hay
     */
    private function hasPair(array $cards): ?int
    {
        if ($cards[0]->getNumber() === $cards[1]->getNumber()) {
            return $cards[0]->getNumber();
        }
        return null;
    }

    public function dealInitialCards(Game $game): void
    {
        $availableCards = [];
        
        // Crear una carta de cada palo
        $suits = ['hearts', 'diamonds', 'clubs', 'spades'];
        foreach ($suits as $suit) {
            $number = rand(1, 10);
            $availableCards[] = new Card($number, $suit);
        }
        
        // Guardar las cartas disponibles en el juego
        $game->setAvailableCards($availableCards);
    }
} 