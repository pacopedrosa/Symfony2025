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
        $gameMode = $game->getGameMode();
        
        if ($gameMode === 1) {
            return $this->determineWinnerOneCard($game);
        } elseif ($gameMode === 2) {
            return $this->determineWinnerTwoCards($game);
        } else {
            return $this->determineWinnerThreeCards($game);
        }
    }

    private function determineWinnerOneCard(Game $game): ?User
    {
        $player1Card = $game->getPlayer1Card1();
        $player2Card = $game->getPlayer2Card1();

        if ($player1Card->getNumber() > $player2Card->getNumber()) {
            return $game->getPlayer1();
        } elseif ($player2Card->getNumber() > $player1Card->getNumber()) {
            return $game->getPlayer2();
        }
        return null; // Empate
    }

    private function determineWinnerTwoCards(Game $game): ?User
    {
        $player1Cards = [$game->getPlayer1Card1(), $game->getPlayer1Card2()];
        $player2Cards = [$game->getPlayer2Card1(), $game->getPlayer2Card2()];
    
        // Check for pairs first
        $player1Pair = $this->hasPair($player1Cards);
        $player2Pair = $this->hasPair($player2Cards);
    
        // If both have pairs, highest wins
        if ($player1Pair && $player2Pair) {
            if ($player1Pair > $player2Pair) {
                return $game->getPlayer1();
            } elseif ($player2Pair > $player1Pair) {
                return $game->getPlayer2();
            }
            return null; // Tie if pairs are equal
        }
    
        // If only one has pair, they win
        if ($player1Pair) return $game->getPlayer1();
        if ($player2Pair) return $game->getPlayer2();
    
        // If no pairs, compare highest cards first, then second highest
        $player1Numbers = [$player1Cards[0]->getNumber(), $player1Cards[1]->getNumber()];
        $player2Numbers = [$player2Cards[0]->getNumber(), $player2Cards[1]->getNumber()];
        rsort($player1Numbers); // Sort in descending order
        rsort($player2Numbers);
    
        // Compare highest cards
        if ($player1Numbers[0] > $player2Numbers[0]) {
            return $game->getPlayer1();
        } elseif ($player2Numbers[0] > $player1Numbers[0]) {
            return $game->getPlayer2();
        }
    
        // If highest cards are equal, compare second highest
        if ($player1Numbers[1] > $player2Numbers[1]) {
            return $game->getPlayer1();
        } elseif ($player2Numbers[1] > $player1Numbers[1]) {
            return $game->getPlayer2();
        }
    
        return null; // Tie if both cards are equal
    }
    private function determineWinnerThreeCards(Game $game): ?User
    {
        $player1Cards = [$game->getPlayer1Card1(), $game->getPlayer1Card2(), $game->getPlayer1Card3()];
        $player2Cards = [$game->getPlayer2Card1(), $game->getPlayer2Card2(), $game->getPlayer2Card3()];
    
        // Check for trios first
        $player1Trio = $this->hasTrio($player1Cards);
        $player2Trio = $this->hasTrio($player2Cards);
    
        // If both have trios, highest wins
        if ($player1Trio && $player2Trio) {
            if ($player1Trio > $player2Trio) {
                return $game->getPlayer1();
            } elseif ($player2Trio > $player1Trio) {
                return $game->getPlayer2();
            }
            return null; // Tie if trios are equal
        }
    
        // If only one has trio, they win
        if ($player1Trio) return $game->getPlayer1();
        if ($player2Trio) return $game->getPlayer2();
    
        // Check for pairs if no trios
        $player1Pair = $this->hasPair([$player1Cards[0], $player1Cards[1]]) ?? 
                      $this->hasPair([$player1Cards[1], $player1Cards[2]]) ?? 
                      $this->hasPair([$player1Cards[0], $player1Cards[2]]);
        $player2Pair = $this->hasPair([$player2Cards[0], $player2Cards[1]]) ?? 
                      $this->hasPair([$player2Cards[1], $player2Cards[2]]) ?? 
                      $this->hasPair([$player2Cards[0], $player2Cards[2]]);
    
        // If both have pairs, highest wins
        if ($player1Pair && $player2Pair) {
            if ($player1Pair > $player2Pair) {
                return $game->getPlayer1();
            } elseif ($player2Pair > $player1Pair) {
                return $game->getPlayer2();
            }
            return null; // Tie if pairs are equal
        }
    
        // If only one has pair, they win
        if ($player1Pair) return $game->getPlayer1();
        if ($player2Pair) return $game->getPlayer2();
    
        // If no pairs, highest card wins
        $player1Highest = max($player1Cards[0]->getNumber(), $player1Cards[1]->getNumber(), $player1Cards[2]->getNumber());
        $player2Highest = max($player2Cards[0]->getNumber(), $player2Cards[1]->getNumber(), $player2Cards[2]->getNumber());
    
        if ($player1Highest > $player2Highest) {
            return $game->getPlayer1();
        } elseif ($player2Highest > $player1Highest) {
            return $game->getPlayer2();
        }
    
        return null; // Tie if highest cards are equal
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
    private function hasTrio(array $cards): ?int
    {
        if (count($cards) !== 3) return null;
        
        if ($cards[0]->getNumber() === $cards[1]->getNumber() && 
            $cards[1]->getNumber() === $cards[2]->getNumber()) {
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