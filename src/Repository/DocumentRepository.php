<?php

namespace App\Repository;

use App\Entity\ClientCase;
use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Document>
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function findAllByClientCase(ClientCase $clientCase, string $direction = 'DESC'): array
    {
        $qb = $this->createQueryBuilder('d');

        $qb
            ->andWhere('d.clientCase = :client_case')
            ->setParameter('client_case', $clientCase)
            ->addOrderBy('d.createdAt', $direction);

        return $qb->getQuery()->getResult();
    }
}
