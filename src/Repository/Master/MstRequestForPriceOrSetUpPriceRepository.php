<?php

namespace App\Repository\Master;

use App\Entity\Master\MstRequestForPriceOrSetUpPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstRequestForPriceOrSetUpPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstRequestForPriceOrSetUpPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstRequestForPriceOrSetUpPrice[]    findAll()
 * @method MstRequestForPriceOrSetUpPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstRequestForPriceOrSetUpPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstRequestForPriceOrSetUpPrice::class);
    }

    // /**
    //  * @return MstRequestForPriceOrSetUpPrice[] Returns an array of MstRequestForPriceOrSetUpPrice objects
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
    public function findOneBySomeField($value): ?MstRequestForPriceOrSetUpPrice
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
