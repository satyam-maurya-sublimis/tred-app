<?php

namespace App\Repository\Master;

use App\Entity\Master\MstProductRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstProductRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstProductRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstProductRating[]    findAll()
 * @method MstProductRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstProductRatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstProductRating::class);
    }

    // /**
    //  * @return MstProductRating[] Returns an array of MstProductRating objects
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
    public function findOneBySomeField($value): ?MstProductRating
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
