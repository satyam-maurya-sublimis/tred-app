<?php

namespace App\Repository\Master;

use App\Entity\Master\MstAccountStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstAccountStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstAccountStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstAccountStatus[]    findAll()
 * @method MstAccountStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstAccountStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstAccountStatus::class);
    }

    // /**
    //  * @return MstAccountStatus[] Returns an array of MstAccountStatus objects
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
    public function findOneBySomeField($value): ?MstAccountStatus
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
