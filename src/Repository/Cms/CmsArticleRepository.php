<?php

namespace App\Repository\Cms;

use App\Entity\Cms\CmsArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method CmsArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsArticle[]    findAll()
 * @method CmsArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsArticle::class);
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

    public function getAreaInterestList($category_id, $company_id)
    {
        return $this->createQueryBuilder('a')
            ->select('i.id', 'i.areaInterest')
            ->leftJoin('a.mstAreaInterest', 'i')
            ->andWhere('a.mstArticleCategory =:category')
            ->andWhere('a.orgCompany =:company')
            ->andWhere('a.isActive =:active')
            ->setParameter('category', $category_id)
            ->setParameter('company', $company_id)
            ->setParameter('active', 1)
            ->groupBy('i.id')
            ->orderBy('i.areaInterest', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getArticleByInterestId($interest_id, $company_id)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.mstAreaInterest', 'i')
            ->andWhere('i.id =:interest')
            ->andWhere('a.orgCompany =:company')
            ->andWhere('a.isActive =:active')
            ->setParameter('interest', $interest_id)
            ->setParameter('company', $company_id)
            ->setParameter('active', 1)
            ->orderBy('a.articleCreateDateTime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getChangeMakerBySearchParam($searchParam, $company_id)
    {

        return $this->createQueryBuilder('a')
            ->where('a.articleFor LIKE :searchparam')
            ->orWhere('a.locationName LIKE :searchparam')
            ->orWhere('a.cityName LIKE :searchparam')
            ->andWhere('a.orgCompany =:company')
            ->andWhere('a.isActive =:active')
            ->setParameter('searchparam', '%'.$searchParam.'%')
            ->setParameter('company', $company_id)
            ->setParameter('active', 1)
            ->getQuery()
            ->getResult();
    }

    public function getChangeMakerBySearchParamAndInterest($searchParam, $interest_id, $company_id)
    {

        return $this->createQueryBuilder('a')
            ->leftJoin('a.mstAreaInterest', 'i')
            ->where('a.articleFor LIKE :searchparam')
            ->orWhere('a.locationName LIKE :searchparam')
            ->orWhere('a.cityName LIKE :searchparam')
            ->andWhere('i.id =:interest')
            ->andWhere('a.orgCompany =:company')
            ->andWhere('a.isActive =:active')
            ->setParameter('searchparam', '%'.$searchParam.'%')
            ->setParameter('interest', $interest_id)
            ->setParameter('company', $company_id)
            ->setParameter('active', 1)
            ->getQuery()
            ->getResult();
    }

    public function getBlogBySearchParam($searchParam, $company_id)
    {

        return $this->createQueryBuilder('a')
            ->where('a.articleTitle LIKE :searchparam')
            ->andWhere('a.orgCompany =:company')
            ->andWhere('a.isActive =:active')
            ->setParameter('searchparam', '%'.$searchParam.'%')
            ->setParameter('company', $company_id)
            ->setParameter('active', 1)
            ->getQuery()
            ->getResult();
    }

    public function getArticleByCategory($category_id,$company_id)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.mstArticleCategory','ac')
            ->andWhere('ac.id =:category')
            ->andWhere('a.orgCompany =:company')
            ->setParameter('category', $category_id)
            ->setParameter('company', $company_id)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getContentByArticle($page)
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
            $dql = $this->createQueryBuilder('a')
                ->andWhere('a.articleSlugName = :val')
                ->setParameter('val', $slugName)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
        return $dql;
    }


}
