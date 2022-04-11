<?php

namespace App\Repository\Master;

use App\Entity\Master\MstProjectAmenities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstProjectAmenities|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstProjectAmenities|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstProjectAmenities[]    findAll()
 * @method MstProjectAmenities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstProjectAmenitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstProjectAmenities::class);
    }

    public function getAmenitiesBySubCategoryId($subCategoryId)
    {
        return $this->createQueryBuilder('s')
            ->select("s.id,s.projectAmenities as name")
            ->innerJoin('s.mstSubCategory','msc')
            ->andWhere('msc.id = :val')
            ->andWhere('s.isActive = :active')
            ->setParameter('val', $subCategoryId)
            ->setParameter('active', 1)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return MstProjectAmenities[] Returns an array of MstProjectAmenities objects
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
    public function findOneBySomeField($value): ?MstProjectAmenities
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
