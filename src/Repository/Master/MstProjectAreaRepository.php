<?php

namespace App\Repository\Master;

use App\Entity\Master\MstProjectArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstProjectArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstProjectArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstProjectArea[]    findAll()
 * @method MstProjectArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstProjectAreaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstProjectArea::class);
    }

    // /**
    //  * @return MstProjectArea[] Returns an array of MstProjectArea objects
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
    public function findOneBySomeField($value): ?MstProjectArea
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
