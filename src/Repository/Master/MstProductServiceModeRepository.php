<?php

namespace App\Repository\Master;

use App\Entity\Master\MstProductServiceMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstProductServiceMode|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstProductServiceMode|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstProductServiceMode[]    findAll()
 * @method MstProductServiceMode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstProductServiceModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstProductServiceMode::class);
    }

    // /**
    //  * @return MstProductServiceMode[] Returns an array of MstProductServiceMode objects
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
    public function findOneBySomeField($value): ?MstProductServiceMode
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
