<?php

namespace App\Repository\Master;

use App\Entity\Master\MstDeviceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstDeviceType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstDeviceType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstDeviceType[]    findAll()
 * @method MstDeviceType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstDeviceTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstDeviceType::class);
    }

    // /**
    //  * @return MstDeviceType[] Returns an array of MstDeviceType objects
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
    public function findOneBySomeField($value): ?MstDeviceType
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
