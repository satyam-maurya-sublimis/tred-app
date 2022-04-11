<?php

namespace App\Repository\Master;

use App\Entity\Master\MstPropertyIn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstPropertyIn|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstPropertyIn|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstPropertyIn[]    findAll()
 * @method MstPropertyIn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstPropertyInRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstPropertyIn::class);
    }

    // /**
    //  * @return MstPropertyIn[] Returns an array of MstPropertyIn objects
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
    public function findOneBySomeField($value): ?MstPropertyIn
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
