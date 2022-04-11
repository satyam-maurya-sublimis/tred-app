<?php

namespace App\Repository\Master;

use App\Entity\Master\MstDesignation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstDesignation|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstDesignation|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstDesignation[]    findAll()
 * @method MstDesignation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstDesignationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstDesignation::class);
    }

    // /**
    //  * @return MstDesignation[] Returns an array of MstDesignation objects
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
    public function findOneBySomeField($value): ?MstDesignation
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
