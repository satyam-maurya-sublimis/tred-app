<?php

namespace App\Repository\Form;

use App\Entity\Form\FormEnquiry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormEnquiry|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormEnquiry|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormEnquiry[]    findAll()
 * @method FormEnquiry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormEnquiryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormEnquiry::class);
    }

    public function getFormEnquiry()
    {
        return $this->createQueryBuilder('f')
            ->select('f.enquiryForm')
            ->groupBy('f.enquiryForm')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return FormEnquiry[] Returns an array of FormEnquiry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FormEnquiry
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
