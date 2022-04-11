<?php

namespace App\Repository\Product;

use App\Entity\Product\PrdProductVariantOptionList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrdProductVariantOptionList|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrdProductVariantOptionList|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrdProductVariantOptionList[]    findAll()
 * @method PrdProductVariantOptionList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrdProductVariantOptionListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrdProductVariantOptionList::class);
    }

    public function deleteExistingOptionList($variant_id)
    {
        return $this->createQueryBuilder('vol')
            ->delete()
            ->where('vol.prdProductVariant =:variant')
            ->setParameter('variant', $variant_id)
            ->getQuery()
            ->getResult();

    }

    public function getVariantOption($variant_id)
    {
        return $this->createQueryBuilder('o')

            ->leftJoin('o.prdOptionList', 'l')
            ->andWhere('o.prdProductVariant =:variant')
            ->setParameter('variant', $variant_id)
            ->getQuery()
            ->getSingleResult();
    }


}
