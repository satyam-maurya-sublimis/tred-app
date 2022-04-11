<?php

namespace App\Repository\Master;

use App\Entity\Master\MstProjectFloor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstProjectFloor|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstProjectFloor|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstProjectFloor[]    findAll()
 * @method MstProjectFloor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstProjectFloorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstProjectFloor::class);
    }

    // /**
    //  * @return MstProjectFloor[] Returns an array of MstProjectFloor objects
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
    public function findOneBySomeField($value): ?MstProjectFloor
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
