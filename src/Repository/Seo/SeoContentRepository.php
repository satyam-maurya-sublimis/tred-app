<?php

namespace App\Repository\Seo;

use App\Entity\Seo\SeoContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SeoContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method SeoContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method SeoContent[]    findAll()
 * @method SeoContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeoContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeoContent::class);
    }
}
