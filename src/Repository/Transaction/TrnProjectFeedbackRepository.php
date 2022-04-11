<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnProjectFeedback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnProjectFeedback|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnProjectFeedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnProjectFeedback[]    findAll()
 * @method TrnProjectFeedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnProjectFeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnProjectFeedback::class);
    }

    // /**
    //  * @return TrnProjectFeedback[] Returns an array of TrnProjectFeedback objects
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
    public function findOneBySomeField($value): ?TrnProjectFeedback
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
