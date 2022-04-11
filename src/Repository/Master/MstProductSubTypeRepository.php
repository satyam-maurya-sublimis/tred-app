<?php

namespace App\Repository\Master;

use App\Entity\Master\MstProductSubType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstProductSubType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstProductSubType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstProductSubType[]    findAll()
 * @method MstProductSubType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstProductSubTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstProductSubType::class);
    }

    public function getProductSubTypeByProductTypeId($productTypeId)
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.mstProductType','t')
            ->andWhere('t.id = :val')
            ->andWhere('t.isActive = :active')
            ->andWhere('s.isActive = :active')
            ->setParameter('val', $productTypeId)
            ->setParameter('active', 1)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return MstProductSubType[] Returns an array of MstProductSubType objects
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
    public function findOneBySomeField($value): ?MstProductSubType
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
