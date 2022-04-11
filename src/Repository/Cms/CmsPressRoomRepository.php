<?php

namespace App\Repository\Cms;

use App\Entity\Cms\CmsPressRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CmsPressRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsPressRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsPressRoom[]    findAll()
 * @method CmsPressRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsPressRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsPressRoom::class);
    }

    public function getPressRelease()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isActive = :val')
            ->setParameter('val', 1)
            ->orderBy('c.articleDateTime', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return CmsPressRoom[] Returns an array of CmsPressRoom objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CmsPressRoom
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
