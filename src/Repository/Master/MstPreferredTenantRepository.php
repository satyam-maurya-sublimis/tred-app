<?php

namespace App\Repository\Master;

use App\Entity\Master\MstPreferredTenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstPreferredTenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstPreferredTenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstPreferredTenant[]    findAll()
 * @method MstPreferredTenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstPreferredTenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstPreferredTenant::class);
    }

    // /**
    //  * @return MstPreferredTenant[] Returns an array of MstPreferredTenant objects
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
    public function findOneBySomeField($value): ?MstPreferredTenant
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
