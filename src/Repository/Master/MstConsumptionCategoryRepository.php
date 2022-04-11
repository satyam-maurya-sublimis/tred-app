<?php

namespace App\Repository\Master;

use App\Entity\Master\MstConsumptionCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstConsumptionCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstConsumptionCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstConsumptionCategory[]    findAll()
 * @method MstConsumptionCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstConsumptionCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstConsumptionCategory::class);
    }

    // /**
    //  * @return MstConsumptionCategory[] Returns an array of MstConsumptionCategory objects
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
    public function findOneBySomeField($value): ?MstConsumptionCategory
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
