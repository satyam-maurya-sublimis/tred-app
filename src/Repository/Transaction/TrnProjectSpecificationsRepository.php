<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnProjectSpecifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnProjectSpecifications|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnProjectSpecifications|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnProjectSpecifications[]    findAll()
 * @method TrnProjectSpecifications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnProjectSpecificationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnProjectSpecifications::class);
    }

    // /**
    //  * @return TrnProjectSpecifications[] Returns an array of TrnProjectSpecifications objects
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
    public function findOneBySomeField($value): ?TrnProjectSpecifications
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
