<?php

namespace App\Repository\Product;

use App\Entity\Product\PrdOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrdOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrdOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrdOption[]    findAll()
 * @method PrdOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrdOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrdOption::class);
    }

}
