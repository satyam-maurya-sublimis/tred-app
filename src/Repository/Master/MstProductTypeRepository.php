<?php

namespace App\Repository\Master;

use App\Entity\Master\MstProductType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstProductType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstProductType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstProductType[]    findAll()
 * @method MstProductType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstProductTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstProductType::class);
    }
    public function getProductType($customerType) {
        $dql = $this->createQueryBuilder('c');
        if ($customerType == 'b2c')
        {
            $dql->andWhere('c.productType != :product')
                ->setParameter('product', 'Material');
        }
        return $dql->andWhere('c.isActive = :active')
            ->setParameter('active', 1)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getNavigationProductType() {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isActive = :active')
            ->andWhere('c.isPortal = :active')
            ->setParameter('active', 1)
            ->orderBy('c.position')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getProductTypeByCategoryId($category_id)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.mstProductCategory = :val')
            ->andWhere('s.isActive = :active')
            ->setParameter('val', $category_id)
            ->setParameter('active', 1)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getFurnitureProductType($slugName)
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.mstProductCategory','c')
            ->andWhere('c.productCategorySlugName = :val')
            ->andWhere('s.isActive = :active')
            ->andWhere('c.isActive = :active')
            ->setParameter('val', $slugName)
            ->setParameter('active', 1)
            ->orderBy('s.productTypePosition','asc')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return MstProductType[] Returns an array of MstProductType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MstProductType
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
