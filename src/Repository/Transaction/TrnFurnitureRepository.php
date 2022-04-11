<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnFurniture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnFurniture|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnFurniture|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnFurniture[]    findAll()
 * @method TrnFurniture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnFurnitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnFurniture::class);
    }

    public function getFurnitureProductTypeSlugName($slugName){
        return $this->createQueryBuilder('p')
            ->innerJoin('p.mstProductType', 'prd')
            ->andwhere('p.isActive = :active')
            ->andwhere('prd.isActive = :active')
            ->andwhere('prd.productTypeSlugName = :slugname')
            ->setParameter('active', 1)
            ->setParameter('slugname', $slugName)
            ->getQuery()
            ->getResult()
            ;

    }
}
