<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnFurnitureUniqueValuePreposition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnFurnitureUniqueValuePreposition|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnFurnitureUniqueValuePreposition|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnFurnitureUniqueValuePreposition[]    findAll()
 * @method TrnFurnitureUniqueValuePreposition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnFurnitureUniqueValuePrepositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnFurnitureUniqueValuePreposition::class);
    }
}
