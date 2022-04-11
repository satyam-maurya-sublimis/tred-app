<?php

namespace App\Repository\Master;

use App\Entity\Master\MstBrand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstBrand|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstBrand|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstBrand[]    findAll()
 * @method MstBrand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstBrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstBrand::class);
    }

    // /**
    //  * @return MstBrand[] Returns an array of MstBrand objects
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
    public function findOneBySomeField($value): ?MstBrand
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
