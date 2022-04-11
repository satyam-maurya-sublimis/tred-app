<?php

namespace App\Repository\Master;

use App\Entity\Master\MstNatureOfBusiness;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstNatureOfBusiness|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstNatureOfBusiness|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstNatureOfBusiness[]    findAll()
 * @method MstNatureOfBusiness[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstNatureOfBusinessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstNatureOfBusiness::class);
    }

    // /**
    //  * @return MstNatureOfBusiness[] Returns an array of MstNatureOfBusiness objects
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
    public function findOneBySomeField($value): ?MstNatureOfBusiness
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
