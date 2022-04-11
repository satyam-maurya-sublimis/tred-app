<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnVendorPartnerDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnVendorPartnerDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnVendorPartnerDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnVendorPartnerDetails[]    findAll()
 * @method TrnVendorPartnerDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnVendorPartnerDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnVendorPartnerDetails::class);
    }

    public function getVendorPartnerlist($vendorTypeId,$id=null)
    {
       if ($id){
           return $this->createQueryBuilder('s')
               ->select("s.id,s.vendorPartnerName as name")
               ->andWhere('s.isActive = :active')
               ->andWhere('s.id = :id')
               ->setParameter('id', $id)
               ->setParameter('active', 1)
               ->getQuery()
               ->getResult()
               ;
       }else{
           return $this->createQueryBuilder('s')
               ->select("s.id,s.vendorPartnerName as name")
               ->innerJoin('s.mstVendorType','msc')
               ->andWhere('msc.id = :val')
               ->andWhere('s.isActive = :active')
               ->setParameter('val', $vendorTypeId)
               ->setParameter('active', 1)
               ->getQuery()
               ->getResult()
               ;
       }
    }

    public function getVendorPartnerCities(){
        $dql = $this->createQueryBuilder('t')
            ->select('c.id','c.city')
            ->innerJoin('t.mstCitiesOperatingIn','c')
//            ->innerJoin('t.mstRating','r')
            ->andWhere('t.isActive = :active')
//            ->andWhere('r.isActive = :active')
//            ->andWhere('r.rating = :rating')
            ->setParameter('active', 1)
//            ->setParameter('rating', 5)
            ->getQuery()
        ;
        return $dql->getResult();
    }
    // /**
    //  * @return TrnVendorPartnerDetails[] Returns an array of TrnVendorPartnerDetails objects
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
    public function findOneBySomeField($value): ?TrnVendorPartnerDetails
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
