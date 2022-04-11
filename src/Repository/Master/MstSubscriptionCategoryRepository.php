<?php

namespace App\Repository\Master;

use App\Entity\Master\MstSubscriptionCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstSubscriptionCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstSubscriptionCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstSubscriptionCategory[]    findAll()
 * @method MstSubscriptionCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstSubscriptionCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstSubscriptionCategory::class);
    }

    // /**
    //  * @return MstSubscriptionCategory[] Returns an array of MstSubscriptionCategory objects
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
    public function findOneBySomeField($value): ?MstSubscriptionCategory
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
