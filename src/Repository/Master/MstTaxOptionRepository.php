<?php

namespace App\Repository\Master;

use App\Entity\Master\MstTaxOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstTaxOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstTaxOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstTaxOption[]    findAll()
 * @method MstTaxOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstTaxOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstTaxOption::class);
    }

    // /**
    //  * @return MstTaxOption[] Returns an array of MstTaxOption objects
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
    public function findOneBySomeField($value): ?MstTaxOption
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
