<?php

namespace App\Repository\Master;

use App\Entity\Master\MstMeetingStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstMeetingStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstMeetingStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstMeetingStatus[]    findAll()
 * @method MstMeetingStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstMeetingStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstMeetingStatus::class);
    }

    // /**
    //  * @return MstMeetingStatus[] Returns an array of MstMeetingStatus objects
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
    public function findOneBySomeField($value): ?MstMeetingStatus
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
