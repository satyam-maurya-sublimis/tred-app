<?php

namespace App\Repository\Master;

use App\Entity\Master\MstCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstCategory[]    findAll()
 * @method MstCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstCategory::class);
    }

    // /**
    //  * @return MstCategory[] Returns an array of MstCategory objects
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
    public function findOneBySomeField($value): ?MstCategory
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
