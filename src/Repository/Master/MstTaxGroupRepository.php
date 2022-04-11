<?php

namespace App\Repository\Master;

use App\Entity\Master\MstTaxGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstTaxGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstTaxGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstTaxGroup[]    findAll()
 * @method MstTaxGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstTaxGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstTaxGroup::class);
    }

    // /**
    //  * @return MstTaxGroup[] Returns an array of MstTaxGroup objects
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
    public function findOneBySomeField($value): ?MstTaxGroup
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
