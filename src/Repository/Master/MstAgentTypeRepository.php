<?php

namespace App\Repository\Master;

use App\Entity\Master\MstAgentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstAgentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstAgentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstAgentType[]    findAll()
 * @method MstAgentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstAgentTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstAgentType::class);
    }

    // /**
    //  * @return MstAgentType[] Returns an array of MstAgentType objects
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
    public function findOneBySomeField($value): ?MstAgentType
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
