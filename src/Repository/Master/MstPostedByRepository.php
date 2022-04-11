<?php

namespace App\Repository\Master;

use App\Entity\Master\MstPostedBy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstPostedBy|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstPostedBy|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstPostedBy[]    findAll()
 * @method MstPostedBy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstPostedByRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstPostedBy::class);
    }

    // /**
    //  * @return MstPostedBy[] Returns an array of MstPostedBy objects
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
    public function findOneBySomeField($value): ?MstPostedBy
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
