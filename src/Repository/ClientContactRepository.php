<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\ClientContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientContact>
 */
class ClientContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientContact::class);
    }

    public function searchPaginated(?Client $client, int $page, int $itemPerPage): array
    {
        if (!$client) {
            return [];
        }

        $offset = $page * $itemPerPage;

        $queryBuilder = $this->createQueryBuilder('client_contact');

        $queryBuilder
            ->leftJoin('client_contact.client', 'client')
            ->andWhere('client = :client')
            ->setParameter('client', $client)
            ->addOrderBy('client_contact.createdAt', 'DESC')
            ->setMaxResults($offset);

        return $queryBuilder->getQuery()->getResult();
    }
}
