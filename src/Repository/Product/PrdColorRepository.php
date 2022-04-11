<?php

namespace App\Repository\Product;

use App\Entity\Product\PrdColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrdColor|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrdColor|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrdColor[]    findAll()
 * @method PrdColor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrdColorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrdColor::class);
    }

}
