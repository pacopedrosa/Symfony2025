<?php

namespace App\Repository;

use App\Entity\Matchs;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Matchs>
 */
class MatchsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matchs::class);
    }

    /**
     * @return Matchs[] Returns an array of open Matchs objects
     */
    public function findOpenMatches(): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.open = :open')
            ->setParameter('open', true)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Matchs[] Returns an array of match history for the user
     */
    public function findUserMatchHistory(User $user): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.relationUser = :user OR m.oponent = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Matchs[] Returns an array of Matchs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Matchs
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
