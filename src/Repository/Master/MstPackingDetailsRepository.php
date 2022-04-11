<?php

namespace App\Repository\Master;

use App\Entity\Master\MstPackingDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstPackingDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstPackingDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstPackingDetails[]    findAll()
 * @method MstPackingDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstPackingDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstPackingDetails::class);
    }

    // /**
    //  * @return MstPackingDetails[] Returns an array of MstPackingDetails objects
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
    public function findOneBySomeField($value): ?MstPackingDetails
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
