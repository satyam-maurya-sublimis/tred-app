<?php

namespace App\Repository\Master;

use App\Entity\Master\MstTax;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstTax|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstTax|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstTax[]    findAll()
 * @method MstTax[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstTaxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstTax::class);
    }

    // /**
    //  * @return MstTax[] Returns an array of MstTax objects
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
    public function findOneBySomeField($value): ?MstTax
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
