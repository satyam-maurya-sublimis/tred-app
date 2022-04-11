<?php

namespace App\Repository\Product;

use App\Entity\Product\PrdOptionList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrdOptionList|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrdOptionList|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrdOptionList[]    findAll()
 * @method PrdOptionList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrdOptionListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrdOptionList::class);
    }

}
