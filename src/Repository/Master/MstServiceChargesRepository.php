<?php

namespace App\Repository\Master;

use App\Entity\Master\MstServiceCharges;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstServiceCharges|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstServiceCharges|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstServiceCharges[]    findAll()
 * @method MstServiceCharges[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstServiceChargesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstServiceCharges::class);
    }

    // /**
    //  * @return MstServiceCharges[] Returns an array of MstServiceCharges objects
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
    public function findOneBySomeField($value): ?MstServiceCharges
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
