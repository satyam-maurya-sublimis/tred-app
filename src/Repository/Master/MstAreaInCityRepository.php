<?php

namespace App\Repository\Master;

use App\Entity\Master\MstAreaInCity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstAreaInCity|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstAreaInCity|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstAreaInCity[]    findAll()
 * @method MstAreaInCity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstAreaInCityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstAreaInCity::class);
    }
    public function getAreaInCityListByCityId($city_id)
    {
        $dql = $this->createQueryBuilder('c')
            ->select('c.id', 'c.area as name')
            ->where('c.mstCity = :val')
            ->setParameter('val', $city_id)
            ->getQuery()
            ->getResult()
        ;
        return $dql;

    }
    // /**
    //  * @return MstAreaInCity[] Returns an array of MstAreaInCity objects
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
    public function findOneBySomeField($value): ?MstAreaInCity
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
