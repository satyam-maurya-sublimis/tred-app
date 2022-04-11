<?php

namespace App\Repository\Master;

use App\Entity\Master\MstProjectSpecification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstProjectSpecification|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstProjectSpecification|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstProjectSpecification[]    findAll()
 * @method MstProjectSpecification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstProjectSpecificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstProjectSpecification::class);
    }

    // /**
    //  * @return MstProjectSpecification[] Returns an array of MstProjectSpecification objects
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
    public function findOneBySomeField($value): ?MstProjectSpecification
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
