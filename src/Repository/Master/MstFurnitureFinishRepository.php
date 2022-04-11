<?php

namespace App\Repository\Master;

use App\Entity\Master\MstFurnitureFinish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MstFurnitureFinish|null find($id, $lockMode = null, $lockVersion = null)
 * @method MstFurnitureFinish|null findOneBy(array $criteria, array $orderBy = null)
 * @method MstFurnitureFinish[]    findAll()
 * @method MstFurnitureFinish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MstFurnitureFinishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MstFurnitureFinish::class);
    }

    public function getFurnitureFinish($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.furnitureFinish LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;

    }
}
