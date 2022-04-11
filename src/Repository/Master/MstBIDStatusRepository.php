<?php

namespace App\Repository\Master;

use App\Entity\Master\MstBIDStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstBIDStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstBIDStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstBIDStatus[]    findAll()
 * @method MstBIDStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstBIDStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstBIDStatus::class);
    }

    // /**
    //  * @return MstBIDStatus[] Returns an array of MstBIDStatus objects
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
    public function findOneBySomeField($value): ?MstBIDStatus
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
