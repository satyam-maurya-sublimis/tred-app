<?php

namespace App\Repository\Master;

use App\Entity\Master\MstFurnishing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstFurnishing|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstFurnishing|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstFurnishing[]    findAll()
 * @method MstFurnishing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstFurnishingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstFurnishing::class);
    }

    // /**
    //  * @return MstFurnishing[] Returns an array of MstFurnishing objects
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
    public function findOneBySomeField($value): ?MstFurnishing
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
