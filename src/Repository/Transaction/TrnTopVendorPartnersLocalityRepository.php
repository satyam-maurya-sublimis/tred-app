<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnTopVendorPartnersLocality;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnTopVendorPartnersLocality|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnTopVendorPartnersLocality|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnTopVendorPartnersLocality[]    findAll()
 * @method TrnTopVendorPartnersLocality[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnTopVendorPartnersLocalityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnTopVendorPartnersLocality::class);
    }

    public function getTopVendorPartnersRealEstates(){
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
}
