<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnVendorPartnerOffices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnVendorPartnerOffices|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnVendorPartnerOffices|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnVendorPartnerOffices[]    findAll()
 * @method TrnVendorPartnerOffices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnVendorPartnerOfficesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnVendorPartnerOffices::class);
    }

    /**
     * @param $vendor_partner_id
     * @return mixed
     */
    public function getVendorPartnerOfficesList($vendor_partner_id)
    {
        $dql = $this->createQueryBuilder('c')
            ->select('c.id', 'c.officeName as name')
            ->where('c.trnVendorPartnerDetails = :val')
            ->setParameter('val', $vendor_partner_id)
            ->getQuery()
            ->getResult()
        ;
        return $dql;

    }

    /**
     * @param $vendor_partner_office_id
     * @return mixed
     */
    public function getVendorPartnerOfficeCategoriesList($vendor_partner_office_id)
    {
        $dql = $this->createQueryBuilder('c')
            ->select('oc.officeCategory as name')
            ->innerJoin('c.mstOfficeCategory', 'oc')
            ->where('c.id = :val')
            ->setParameter('val', $vendor_partner_office_id)
            ->getQuery()
            ->getResult()
        ;
        return $dql;
    }
    // /**
    //  * @return TrnVendorPartnerOffices[] Returns an array of TrnVendorPartnerOffices objects
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
    public function findOneBySomeField($value): ?TrnVendorPartnerOffices
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
