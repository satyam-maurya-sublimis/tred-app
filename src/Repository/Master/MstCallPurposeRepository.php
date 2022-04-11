<?php

namespace App\Repository\Master;

use App\Entity\Master\MstCallPurpose;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstCallPurpose|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstCallPurpose|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstCallPurpose[]    findAll()
 * @method MstCallPurpose[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstCallPurposeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstCallPurpose::class);
    }

    // /**
    //  * @return MstCallPurpose[] Returns an array of MstCallPurpose objects
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
    public function findOneBySomeField($value): ?MstCallPurpose
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
