<?php

namespace App\Repository\Master;

use App\Entity\Master\MstPossession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstPossession|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstPossession|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstPossession[]    findAll()
 * @method MstPossession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstPossessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstPossession::class);
    }

    // /**
    //  * @return MstPossession[] Returns an array of MstPossession objects
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
    public function findOneBySomeField($value): ?MstPossession
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
