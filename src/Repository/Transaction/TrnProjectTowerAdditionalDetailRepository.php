<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnProjectTowerAdditionalDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnProjectTowerAdditionalDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnProjectTowerAdditionalDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnProjectTowerAdditionalDetail[]    findAll()
 * @method TrnProjectTowerAdditionalDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnProjectTowerAdditionalDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnProjectTowerAdditionalDetail::class);
    }

    // /**
    //  * @return TrnProjectTowerAdditionalDetail[] Returns an array of TrnProjectTowerAdditionalDetail objects
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
    public function findOneBySomeField($value): ?TrnProjectTowerAdditionalDetail
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
