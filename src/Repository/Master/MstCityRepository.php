<?php

namespace App\Repository\Master;

use App\Entity\Master\MstCity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstCity|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstCity|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstCity[]    findAll()
 * @method MstCity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstCityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstCity::class);
    }

    public function getCityListByStateId($state_id)
    {
        $dql = $this->createQueryBuilder('c')
            ->select('c.id', 'c.city as name')
            ->where('c.mstState = :val')
            ->setParameter('val', $state_id)
            ->getQuery()
            ->getResult()
        ;
        return $dql;

    }

    public function getCityListByCountryId($value, $country_id)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.city LIKE :city')
            ->andWhere('c.mstCountry = :country')
            ->setParameter('city', '%'.$value.'%')
            ->setParameter('country', $country_id)
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
            ;

    }
    public function getCityByPincodeCity($city, $country)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.mstCountry','cc')
            ->andWhere('c.city =:city')
            ->andWhere('cc.country =:country')
            ->setParameter('city', $city)
            ->setParameter('country', $country)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    public function getIndiaCity($city){
        return $this->createQueryBuilder('c')
            ->innerJoin('c.mstCountry','cc')
            ->andWhere('c.city =:city')
            ->andWhere('cc.id = :countryId')
            ->setParameter('city', $city)
            ->setParameter('countryId', 101)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getTopAgentsCities($city){
        return $this->createQueryBuilder('c')
            ->innerJoin('c.trnTopVendorPartners','d')
            ->andWhere('c.city like :city')
            ->andWhere('d.id != :nill')
            ->setParameter('city', "%".$city."%")
            ->setParameter('nill', 0)
            ->getQuery()
            ->getResult()
            ;
    }
}

