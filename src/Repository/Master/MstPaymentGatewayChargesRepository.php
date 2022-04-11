<?php

namespace App\Repository\Master;

use App\Entity\Master\MstPaymentGatewayCharges;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstPaymentGatewayCharges|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstPaymentGatewayCharges|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstPaymentGatewayCharges[]    findAll()
 * @method MstPaymentGatewayCharges[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstPaymentGatewayChargesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstPaymentGatewayCharges::class);
    }

    // /**
    //  * @return MstPaymentGatewayCharges[] Returns an array of MstPaymentGatewayCharges objects
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
    public function findOneBySomeField($value): ?MstPaymentGatewayCharges
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
