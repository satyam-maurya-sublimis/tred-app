<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnFurnitureProductCatalogDimensionMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnFurnitureProductCatalogDimensionMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnFurnitureProductCatalogDimensionMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnFurnitureProductCatalogDimensionMedia[]    findAll()
 * @method TrnFurnitureProductCatalogDimensionMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnFurnitureProductCatalogDimensionMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnFurnitureProductCatalogDimensionMedia::class);
    }

}
