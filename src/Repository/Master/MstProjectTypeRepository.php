<?php

namespace App\Repository\Master;

use App\Entity\Master\MstProjectType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstProjectType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstProjectType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstProjectType[]    findAll()
 * @method MstProjectType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstProjectTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstProjectType::class);
    }

    // /**
    //  * @return MstProjectType[] Returns an array of MstProjectType objects
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
    public function findOneBySomeField($value): ?MstProjectType
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
