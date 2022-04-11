<?php

namespace App\Repository\Master;

use App\Entity\Master\MstDenomination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstDenomination|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstDenomination|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstDenomination[]    findAll()
 * @method MstDenomination[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstDenominationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstDenomination::class);
    }

    // /**
    //  * @return MstDenomination[] Returns an array of MstDenomination objects
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
    public function findOneBySomeField($value): ?MstDenomination
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
