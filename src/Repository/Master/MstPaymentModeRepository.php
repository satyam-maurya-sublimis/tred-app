<?php

namespace App\Repository\Master;

use App\Entity\Master\MstPaymentMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstPaymentMode|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstPaymentMode|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstPaymentMode[]    findAll()
 * @method MstPaymentMode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstPaymentModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstPaymentMode::class);
    }

    // /**
    //  * @return MstPaymentMode[] Returns an array of MstPaymentMode objects
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
    public function findOneBySomeField($value): ?MstPaymentMode
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
