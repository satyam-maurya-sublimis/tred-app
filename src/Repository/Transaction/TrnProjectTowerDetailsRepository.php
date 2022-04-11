<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnProjectTowerDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnProjectTowerDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnProjectTowerDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnProjectTowerDetails[]    findAll()
 * @method TrnProjectTowerDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnProjectTowerDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnProjectTowerDetails::class);
    }

    public function getTowerDetails($projectId)
    {
        $dql = $this->createQueryBuilder('t');
        if($projectId){
            $dql->innerJoin('t.trnProject','p')
                ->andWhere('p.id = :val')
                ->setParameter('val', $projectId);
        }
        return $dql
            ->andWhere('t.isActive = :active')
            ->setParameter('active', 1)
            ->orderBy('t.id','asc')
            ->getQuery()
            ->getResult()
            ;
    }
}
