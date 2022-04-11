<?php

namespace App\Repository\Master;

use App\Entity\Master\MstPropertyType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstPropertyType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstPropertyType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstPropertyType[]    findAll()
 * @method MstPropertyType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstPropertyTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstPropertyType::class);
    }

    // /**
    //  * @return MstPropertyType[] Returns an array of MstPropertyType objects
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
    public function findOneBySomeField($value): ?MstPropertyType
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
