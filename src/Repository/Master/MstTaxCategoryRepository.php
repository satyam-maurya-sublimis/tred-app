<?php

namespace App\Repository\Master;

use App\Entity\Master\MstTaxCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstTaxCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstTaxCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstTaxCategory[]    findAll()
 * @method MstTaxCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstTaxCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstTaxCategory::class);
    }

    // /**
    //  * @return MstTaxCategory[] Returns an array of MstTaxCategory objects
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
    public function findOneBySomeField($value): ?MstTaxCategory
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
