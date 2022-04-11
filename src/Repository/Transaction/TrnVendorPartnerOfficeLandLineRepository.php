<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnVendorPartnerOfficeLandLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnVendorPartnerOfficeLandLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnVendorPartnerOfficeLandLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnVendorPartnerOfficeLandLine[]    findAll()
 * @method TrnVendorPartnerOfficeLandLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnVendorPartnerOfficeLandLineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnVendorPartnerOfficeLandLine::class);
    }

    // /**
    //  * @return TrnVendorPartnerOfficeLandLine[] Returns an array of TrnVendorPartnerOfficeLandLine objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrnVendorPartnerOfficeLandLine
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
