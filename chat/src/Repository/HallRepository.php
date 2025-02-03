<?php

namespace App\Repository;

use App\Entity\Hall;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hall>
 */
class HallRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hall::class);
    }

    //    /**
    //     * @return Hall[] Returns an array of Hall objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Hall
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findActiveHalls()
    {
        return $this->createQueryBuilder('h')
            ->where('h.status = :status')
            ->setParameter('status', 'active')
            ->getQuery()
            ->getResult();
    }

    public function updateHallStatus(Hall $hall, User $user): void
    {
        // Obtener usuarios activos en la sala excluyendo el usuario actual
        $activeUsers = $this->getEntityManager()
            ->getRepository(User::class)
            ->findActiveUsersInHall($hall, $user);

        // Si no quedan otros usuarios, marcar como inactiva
        if (empty($activeUsers)) {
            $hall->setStatus('inactive');
            $this->getEntityManager()->flush();
        }
    }
}
