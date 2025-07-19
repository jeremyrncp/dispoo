<?php

namespace App\Repository;

use App\Entity\Service;
use App\Entity\Upsell;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Upsell>
 */
class UpsellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Upsell::class);
    }

    public function findByService(Service $service)
    {
        return $this->createQueryBuilder("u")
        ->innerJoin("u.services", "s")
        ->andWhere("s.id = :serviceId")
        ->setParameter("serviceId", $service->getId())
        ->getQuery()
        ->getResult();
    }

    public function findByUser(User $user)
    {
        return $this->createQueryBuilder("u")
            ->innerJoin("u.services", "s")
            ->innerJoin("s.category", "c")
            ->andWhere("c.owner = :user")
            ->setParameter("user", $user)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Upsell[] Returns an array of Upsell objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Upsell
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
