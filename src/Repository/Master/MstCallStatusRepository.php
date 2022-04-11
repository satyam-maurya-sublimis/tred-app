<?php

namespace App\Repository\Master;

use App\Entity\Master\MstCallStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstCallStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstCallStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstCallStatus[]    findAll()
 * @method MstCallStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstCallStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstCallStatus::class);
    }

    // /**
    //  * @return MstCallStatus[] Returns an array of MstCallStatus objects
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
    public function findOneBySomeField($value): ?MstCallStatus
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
