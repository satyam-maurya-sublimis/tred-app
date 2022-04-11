<?php

namespace App\Repository\Cms;

use App\Entity\Cms\CmsPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method CmsPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsPage[]    findAll()
 * @method CmsPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsPage::class);
    }

    public function getParentPage()
    {
        $dql =  $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getArrayResult()
        ;

        $sm['None'] = 0;
        foreach ($dql as $value) {

            $sm[$value['pageName']] = $value['id'];
        }

        return $sm;
    }

    public function getContent($page_id)
    {
        try {
            $dql = $this->createQueryBuilder('c')
                ->select('c.pageName', 'c.pageContent')
                ->andWhere('c.id = :val')
                ->setParameter('val', $page_id)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
        return $dql;
    }

    public function getContentByPage($page)
    {
        try {
            $dql = $this->createQueryBuilder('c')
                ->andWhere('c.pageRoute = :val')
                ->setParameter('val', $page)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
        return $dql;
    }

    public function getContentBySlugName($slugName)
    {
        try {
            $dql = $this->createQueryBuilder('c')
                ->andWhere('c.pageSlugName = :val')
                ->setParameter('val', $slugName)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
        return $dql;
    }

    public function getCmsPage()
    {
        return $this->createQueryBuilder('c')
            ->where('c.pageRoute IS NOT NULL')
            ->andWhere('c.isActive =:active')
            ->setParameter('active', 1)
            ->getQuery()
            ->getResult();
    }

}
