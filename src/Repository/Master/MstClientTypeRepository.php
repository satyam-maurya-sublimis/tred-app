<?php

namespace App\Repository\Master;

use App\Entity\Master\MstClientType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstClientType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstClientType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstClientType[]    findAll()
 * @method MstClientType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstClientTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstClientType::class);
    }

    // /**
    //  * @return MstClientType[] Returns an array of MstClientType objects
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
    public function findOneBySomeField($value): ?MstClientType
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
