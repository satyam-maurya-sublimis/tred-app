<?php

namespace App\Repository\Master;

use App\Entity\Master\MstProjectAreaCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstProjectAreaCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstProjectAreaCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstProjectAreaCategory[]    findAll()
 * @method MstProjectAreaCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstProjectAreaCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstProjectAreaCategory::class);
    }
}
