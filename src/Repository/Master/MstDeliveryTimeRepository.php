<?php

namespace App\Repository\Master;

use App\Entity\Master\MstDeliveryTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstDeliveryTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstDeliveryTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstDeliveryTime[]    findAll()
 * @method MstDeliveryTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstDeliveryTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstDeliveryTime::class);
    }

    // /**
    //  * @return MstDeliveryTime[] Returns an array of MstDeliveryTime objects
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
    public function findOneBySomeField($value): ?MstDeliveryTime
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
