<?php

namespace App\Repository\Form;

use App\Entity\Form\FormFurnitureEnquiry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormFurnitureEnquiry|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormFurnitureEnquiry|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormFurnitureEnquiry[]    findAll()
 * @method FormFurnitureEnquiry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormFurnitureEnquiryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormFurnitureEnquiry::class);
    }

}
