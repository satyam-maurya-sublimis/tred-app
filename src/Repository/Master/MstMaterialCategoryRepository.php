<?php

namespace App\Repository\Master;

use App\Entity\Master\MstMaterialCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstMaterialCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstMaterialCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstMaterialCategory[]    findAll()
 * @method MstMaterialCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstMaterialCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstMaterialCategory::class);
    }

    // /**
    //  * @return MstMaterialCategory[] Returns an array of MstMaterialCategory objects
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
    public function findOneBySomeField($value): ?MstMaterialCategory
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
