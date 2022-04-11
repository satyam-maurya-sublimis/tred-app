<?php

namespace App\Repository\Master;

use App\Entity\Master\MstPincode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstPincode|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstPincode|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstPincode[]    findAll()
 * @method MstPincode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstPincodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstPincode::class);
    }

    public function getPincodelist($pincode)
    {
        return $this->createQueryBuilder('s')
            ->select("s.id,concat(s.pincode,' : ',s.officeName) as name")
            ->andWhere('s.pincode = :val')
            ->andWhere('s.delivery = :delivery')
            ->setParameter('val', $pincode)
            ->setParameter('delivery', 'Delivery')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getPincodelistByCityName($cityName)
    {
        return $this->createQueryBuilder('s')
            ->select("s.id,concat(s.officeName,' : (',s.pincode,')') as name")
            ->andWhere('s.district like :val')
            ->andWhere('s.delivery = :delivery')
            ->setParameter('val', '%'.$cityName.'%')
            ->setParameter('delivery', 'Delivery')
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return MstPincode[] Returns an array of MstPincode objects
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
    public function findOneBySomeField($value): ?MstPincode
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
