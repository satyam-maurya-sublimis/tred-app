<?php

namespace App\Repository\Master;

use App\Entity\Master\MstSubCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstSubCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstSubCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstSubCategory[]    findAll()
 * @method MstSubCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstSubCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstSubCategory::class);
    }

    // /**
    //  * @return MstSubCategory[] Returns an array of MstSubCategory objects
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
    public function findOneBySomeField($value): ?MstSubCategory
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
