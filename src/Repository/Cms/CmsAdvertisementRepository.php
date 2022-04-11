<?php

namespace App\Repository\Cms;

use App\Entity\Cms\CmsAdvertisement;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CmsAdvertisement|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsAdvertisement|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsAdvertisement[]    findAll()
 * @method CmsAdvertisement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsAdvertisementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsAdvertisement::class);
    }

    public function getAdvertisement($page_id)
    {
        $date = new DateTime('now');
        return $this->createQueryBuilder('b')
            ->andWhere('b.cmsPage =:page')
            ->andWhere('b.isActive =:active')
            ->andWhere(':now between b.advertisementValidFromDate AND b.advertisementValidToDate')
            ->setParameter('page', $page_id)
            ->setParameter('active', 1)
            ->setParameter('now', $date->format('Y-m-d'))
            ->orderBy('b.position', 'ASC')
            ->getQuery()
            ->getResult();

    }

    public function findOneBySeqNo($value)
    {
        try {
            return $this->createQueryBuilder('b')
                ->select('MAX(b.position)')
                ->andWhere('b.cmsPage = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }

}
