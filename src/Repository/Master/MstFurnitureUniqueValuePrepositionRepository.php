<?php

namespace App\Repository\Master;

use App\Entity\Master\MstFurnitureUniqueValuePreposition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstFurnitureUniqueValuePreposition|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstFurnitureUniqueValuePreposition|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstFurnitureUniqueValuePreposition[]    findAll()
 * @method MstFurnitureUniqueValuePreposition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstFurnitureUniqueValuePrepositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstFurnitureUniqueValuePreposition::class);
    }
}
