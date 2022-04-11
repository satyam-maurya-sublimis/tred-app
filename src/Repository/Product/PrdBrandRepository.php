<?php

namespace App\Repository\Product;

use App\Entity\Product\PrdBrand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrdBrand|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrdBrand|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrdBrand[]    findAll()
 * @method PrdBrand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrdBrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrdBrand::class);
    }
}
