<?php

namespace App\Repository\Master;

use App\Entity\Master\MstRoomConfiguration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstRoomConfiguration|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstRoomConfiguration|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstRoomConfiguration[]    findAll()
 * @method MstRoomConfiguration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstRoomConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstRoomConfiguration::class);
    }

    // /**
    //  * @return MstRoomConfiguration[] Returns an array of MstRoomConfiguration objects
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
    public function findOneBySomeField($value): ?MstRoomConfiguration
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
