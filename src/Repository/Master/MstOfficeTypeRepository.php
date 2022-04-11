<?php

namespace App\Repository\Master;

use App\Entity\Master\MstOfficeType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstOfficeType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstOfficeType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstOfficeType[]    findAll()
 * @method MstOfficeType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstOfficeTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstOfficeType::class);
    }

    // /**
    //  * @return MstOfficeType[] Returns an array of MstOfficeType objects
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
    public function findOneBySomeField($value): ?MstOfficeType
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
