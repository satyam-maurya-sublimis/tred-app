<?php

namespace App\Repository\Master;

use App\Entity\Master\MstProductFeature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstProductFeature|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstProductFeature|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstProductFeature[]    findAll()
 * @method MstProductFeature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstProductFeatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstProductFeature::class);
    }

    // /**
    //  * @return MstProductFeature[] Returns an array of MstProductFeature objects
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
    public function findOneBySomeField($value): ?MstProductFeature
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
