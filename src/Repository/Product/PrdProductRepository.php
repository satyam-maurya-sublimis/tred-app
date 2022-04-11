<?php

namespace App\Repository\Product;

use App\Entity\Product\PrdProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrdProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrdProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrdProduct[]    findAll()
 * @method PrdProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrdProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrdProduct::class);
    }

    // /**
    //  * @return PrdProduct[] Returns an array of PrdProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PrdProduct
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
