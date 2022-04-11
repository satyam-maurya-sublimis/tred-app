<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnTopVendorPartners;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnTopVendorPartners|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnTopVendorPartners|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnTopVendorPartners[]    findAll()
 * @method TrnTopVendorPartners[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnTopVendorPartnersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnTopVendorPartners::class);
    }

    public function getTopBuilders(){
        $dql = $this->createQueryBuilder('t')
            ->innerJoin('t.trnVendorPartnerDetails','td')
            ->innerJoin('td.mstVendorType','vt')
            ->andWhere('t.isActive = :active')
            ->andWhere('td.isActive= :active')
            ->andWhere('vt.isActive= :active')
            ->andWhere('vt.id = :val')
            ->setParameter('active', 1)
            ->setParameter('val', 1)
            ->getQuery()
            ;
        return $dql->getResult();
    }

    public function getTopRealEstates(){
        $dql = $this->createQueryBuilder('t')
            ->innerJoin('t.trnVendorPartnerDetails','td')
            ->innerJoin('td.mstVendorType','vt')
            ->andWhere('t.isActive = :active')
            ->andWhere('td.isActive= :active')
            ->andWhere('vt.isActive= :active')
            ->andWhere('vt.id = :val')
            ->setParameter('active', 1)
            ->setParameter('val', 2)
            ->getQuery()
        ;
        return $dql->getResult();
    }

    public function getTopAgentCities(){
        $dql = $this->createQueryBuilder('t')
            ->select('c.id','c.city')
            ->innerJoin('t.trnTopVendorPartnersLocalities','d')
            ->innerJoin('d.mstCity','c')
            ->andWhere('t.isActive = :active')
            ->setParameter('active', 1)
            ->getQuery()
        ;
        return $dql->getResult();
    }
    // /**
    //  * @return TrnTopVendorPartners[] Returns an array of TrnTopVendorPartners objects
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
    public function findOneBySomeField($value): ?TrnTopVendorPartners
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
