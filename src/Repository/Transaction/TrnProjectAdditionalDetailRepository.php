<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnProjectAdditionalDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnProjectAdditionalDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnProjectAdditionalDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnProjectAdditionalDetail[]    findAll()
 * @method TrnProjectAdditionalDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnProjectAdditionalDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnProjectAdditionalDetail::class);
    }

    // /**
    //  * @return TrnProjectAdditionalDetail[] Returns an array of TrnProjectAdditionalDetail objects
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
    public function findOneBySomeField($value): ?TrnProjectAdditionalDetail
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
