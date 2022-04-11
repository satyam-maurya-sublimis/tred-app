<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnProjectAreaDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnProjectAreaDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnProjectAreaDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnProjectAreaDetail[]    findAll()
 * @method TrnProjectAreaDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnProjectAreaDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnProjectAreaDetail::class);
    }

    // /**
    //  * @return TrnProjectAreaDetail[] Returns an array of TrnProjectAreaDetail objects
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
    public function findOneBySomeField($value): ?TrnProjectAreaDetail
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
