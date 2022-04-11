<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnFurnitureProductCatalog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnFurnitureProductCatalog|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnFurnitureProductCatalog|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnFurnitureProductCatalog[]    findAll()
 * @method TrnFurnitureProductCatalog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnFurnitureProductCatalogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnFurnitureProductCatalog::class);
    }

}
