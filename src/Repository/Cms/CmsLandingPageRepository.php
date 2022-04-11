<?php

namespace App\Repository\Cms;

use App\Entity\Cms\CmsLandingPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CmsLandingPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsLandingPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsLandingPage[]    findAll()
 * @method CmsLandingPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsLandingPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsLandingPage::class);
    }

    // /**
    //  * @return CmsLandingPage[] Returns an array of CmsLandingPage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CmsLandingPage
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
