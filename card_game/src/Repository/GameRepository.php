<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function hasPlayerSelectedCards(Game $game, User $player): bool
    {
        if ($player === $game->getPlayer1()) {
            return $game->getPlayer1Card1() !== null && $game->getPlayer1Card2() !== null;
        } elseif ($player === $game->getPlayer2()) {
            return $game->getPlayer2Card1() !== null && $game->getPlayer2Card2() !== null;
        }
        return false;
    }

    public function isFinished(Game $game): bool
    {
        return $game->getStatus() === 'finished';
    }

    public function canPlay(Game $game, User $player): bool
    {
        // Si el jugador es el Player1 y no ha seleccionado cartas
        if ($player === $game->getPlayer1() && !$game->getPlayer1Card1()) {
            return true;
        }

        // Si el jugador es el Player2 y no ha seleccionado cartas
        if ($player === $game->getPlayer2() && !$game->getPlayer2Card1()) {
            return true;
        }

        return false;
    }

    public function areAllCardsSelected(Game $game): bool
    {
        return $game->getPlayer1Card1() !== null && 
               $game->getPlayer1Card2() !== null && 
               $game->getPlayer2Card1() !== null && 
               $game->getPlayer2Card2() !== null;
    }

    public function findByPlayer(User $player)
    {
        return $this->createQueryBuilder('g')
            ->where('g.player1 = :player OR g.player2 = :player')
            ->setParameter('player', $player)
            ->orderBy('g.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOpenGames()
    {
        return $this->createQueryBuilder('g')
            ->where('g.status = :status')
            ->andWhere('g.player2 IS NULL')
            ->setParameter('status', 'open')
            ->orderBy('g.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
} 