<?php

namespace App\Repository\Media;

use App\Entity\Media\MdaFurniture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MdaFurniture|null find($id, $lockMode = null, $lockVersion = null)
 * @method MdaFurniture|null findOneBy(array $criteria, array $orderBy = null)
 * @method MdaFurniture[]    findAll()
 * @method MdaFurniture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MdaFurnitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MdaFurniture::class);
    }

    public function getProductCatalogueMedia($id)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.trnFurnitureProductCatalog', 't')
            ->andwhere('p.isActive = :active')
            ->andwhere('t.isActive = :active')
            ->andwhere('t.id = :id')
            ->setParameter('active', 1)
            ->setParameter('id', $id)
            ->orderBy('p.position','asc')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getProductCatalogueMediaBySeqNo($productCatalogId)
    {
        try {
            return $this->createQueryBuilder('b')
                ->select('MAX(b.position) as cnt')
                ->innerJoin('b.trnFurnitureProductCatalog','t')
                ->andWhere('t.id = :id')
                ->setParameter('id', $productCatalogId)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }

}
