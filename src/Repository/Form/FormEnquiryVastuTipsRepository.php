<?php

namespace App\Repository\Form;

use App\Entity\Form\FormEnquiryVastuTips;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormEnquiryVastuTips|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormEnquiryVastuTips|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormEnquiryVastuTips[]    findAll()
 * @method FormEnquiryVastuTips[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormEnquiryVastuTipsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormEnquiryVastuTips::class);
    }

}
