<?php

namespace App\Repository;

use App\Entity\Appointment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    public function getByUserAndRangeDate(\DateTime $start, \DateTime $end, User $user)
    {
        return $this->createQueryBuilder("a")
                    ->andWhere("a.startDateTime > :start")
                    ->andWhere("a.endDateTime < :end")
                    ->andWhere("a.owner = :owner")
                    ->setParameter("start", $start)
                    ->setParameter("end", $end)
                    ->setParameter("owner", $user)
                    ->orderBy("a.startDateTime", "ASC")
                    ->getQuery()
                    ->getResult();
    }


    public function findByUserAndAboveDate(User $user, \DateTime $date)
    {
        return $this->createQueryBuilder("a")
            ->andWhere("a.startDateTime > :start")
            ->andWhere("a.owner = :owner")
            ->setParameter("start", $date)
            ->setParameter("owner", $user)
            ->orderBy("a.startDateTime", "ASC")
            ->getQuery()
            ->getResult();

    }

    //    /**
    //     * @return Appointment[] Returns an array of Appointment objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Appointment
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
