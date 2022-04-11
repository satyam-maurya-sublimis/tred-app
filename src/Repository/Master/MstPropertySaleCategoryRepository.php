<?php

namespace App\Repository\Master;

use App\Entity\Master\MstPropertySaleCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstPropertySaleCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstPropertySaleCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstPropertySaleCategory[]    findAll()
 * @method MstPropertySaleCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstPropertySaleCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstPropertySaleCategory::class);
    }

    // /**
    //  * @return MstPropertySaleCategory[] Returns an array of MstPropertySaleCategory objects
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
    public function findOneBySomeField($value): ?MstPropertySaleCategory
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
