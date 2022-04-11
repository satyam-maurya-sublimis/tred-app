<?php

namespace App\Repository\Master;

use App\Entity\Master\MstProductUse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstProductUse|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstProductUse|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstProductUse[]    findAll()
 * @method MstProductUse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstProductUseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstProductUse::class);
    }

    // /**
    //  * @return MstProductUse[] Returns an array of MstProductUse objects
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
    public function findOneBySomeField($value): ?MstProductUse
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
