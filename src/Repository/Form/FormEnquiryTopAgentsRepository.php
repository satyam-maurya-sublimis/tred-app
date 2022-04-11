<?php

namespace App\Repository\Form;

use App\Entity\Form\FormEnquiryTopAgents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormEnquiryTopAgents|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormEnquiryTopAgents|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormEnquiryTopAgents[]    findAll()
 * @method FormEnquiryTopAgents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormEnquiryTopAgentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormEnquiryTopAgents::class);
    }

}
