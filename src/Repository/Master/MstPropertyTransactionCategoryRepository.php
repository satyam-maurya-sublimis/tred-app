<?php

namespace App\Repository\Master;

use App\Entity\Master\MstPropertyTransactionCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstPropertyTransactionCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstPropertyTransactionCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstPropertyTransactionCategory[]    findAll()
 * @method MstPropertyTransactionCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstPropertyTransactionCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstPropertyTransactionCategory::class);
    }

    // /**
    //  * @return MstPropertyTransactionCategory[] Returns an array of MstPropertyTransactionCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MstPropertyTransactionCategory
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
