<?php

namespace App\Repository\Master;

use App\Entity\Master\MstTaskStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstTaskStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstTaskStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstTaskStatus[]    findAll()
 * @method MstTaskStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstTaskStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstTaskStatus::class);
    }

    // /**
    //  * @return MstTaskStatus[] Returns an array of MstTaskStatus objects
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
    public function findOneBySomeField($value): ?MstTaskStatus
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
