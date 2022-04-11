<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnUploadDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnUploadDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnUploadDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnUploadDocument[]    findAll()
 * @method TrnUploadDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnUploadDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnUploadDocument::class);
    }

    public function getMediaBySeqNo($projectId)
    {

        try {
            return $this->createQueryBuilder('b')
                ->select('MAX(b.position) as cnt')
                ->innerJoin('b.trnProject','t')
                ->andWhere('t.id = :id')
                ->setParameter('id', $projectId)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }
}
