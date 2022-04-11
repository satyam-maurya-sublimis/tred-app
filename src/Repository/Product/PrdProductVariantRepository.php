<?php

namespace App\Repository\Product;

use App\Entity\Product\PrdProductVariant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrdProductVariant|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrdProductVariant|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrdProductVariant[]    findAll()
 * @method PrdProductVariant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrdProductVariantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrdProductVariant::class);
    }

//    public function getVariantArrayByProductId($product_id)
//    {
//        $result = $this->createQueryBuilder('v')
//            ->select('o.id', 'o.optionName')
//            ->leftJoin('v.prdOption', 'o')
//            ->andWhere('v.prdProduct =:product')
//            ->setParameter('product', $product_id)
//            ->getQuery()
//            ->getArrayResult();
//
//        if ($result) {
//            foreach ($result as $value)
//            {
//                $prdOptions[$value['id']] = $value['optionName'];
//            }
//        } else {
//            $prdOptions = array();
//
//        }
//        return $prdOptions;
//    }

    public function findOneBySeqNo($product_id)
    {
        return $this->createQueryBuilder('v')
            ->select('MAX(v.position)')
            ->andWhere('v.prdProduct =:product')
            ->setParameter('product', $product_id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function getVariantByProduct($product_id)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.prdProduct =:product')
            ->setParameter('product', $product_id)
            ->orderBy('v.productVariantPrice')
            ->getQuery()
            ->getResult();
    }

}
