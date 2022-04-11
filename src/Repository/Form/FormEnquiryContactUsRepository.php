<?php

namespace App\Repository\Form;

use App\Entity\Form\FormEnquiryContactUs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormEnquiryContactUs|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormEnquiryContactUs|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormEnquiryContactUs[]    findAll()
 * @method FormEnquiryContactUs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormEnquiryContactUsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormEnquiryContactUs::class);
    }

}
