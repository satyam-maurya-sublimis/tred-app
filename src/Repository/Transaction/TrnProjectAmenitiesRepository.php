<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnProjectAmenities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnProjectAmenities|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnProjectAmenities|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnProjectAmenities[]    findAll()
 * @method TrnProjectAmenities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnProjectAmenitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnProjectAmenities::class);
    }

    // /**
    //  * @return TrnProjectAmenities[] Returns an array of TrnProjectAmenities objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrnProjectAmenities
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
