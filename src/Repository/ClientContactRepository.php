<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\ClientContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    public function findBySearchQueryBuilder(
        Client $client,
        ?string $query,
        ?string $sort,
        string $direction = 'DESC'
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('cc');

        $subQuery = $this->getEntityManager()->createQueryBuilder()
            ->select('cc_2.id')
            ->from(ClientContact::class, 'cc_2')
            ->orWhere($qb->expr()->like('cc_2.firstname', ':query'))
            ->orWhere($qb->expr()->like('cc_2.lastname', ':query'))
            ->getDQL();


        $qb
            ->andWhere('cc.client = :client')
            ->setParameter('client', $client);

        if ($query) {
            $qb
                ->andWhere($qb->expr()->in('cc.id', $subQuery))
                ->setParameter('query', '%' . $query . '%');
        }

        if ($sort) {
            $qb->orderBy('cc.' . $sort, $direction);
        }

        return $qb;
    }

    public function findByClientQueryBuilder(Client $client): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('client_contact');
        $queryBuilder
            ->andWhere('client_contact.client = :client')
            ->setParameter('client', $client);

        return $queryBuilder;
    }

    public function findByClientIdQueryBuilder(int $clientId): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('client_contact');
        $queryBuilder
            ->andWhere('client_contact.client = :client')
            ->setParameter('client', $clientId);

        return $queryBuilder;
    }

    public function findByClientId(int $clientId): array
    {
        $queryBuilder = $this->createQueryBuilder('client_contact');
        $queryBuilder
            ->andWhere('client_contact.client = :client')
            ->setParameter('client', $clientId);

        return $queryBuilder->getQuery()->getResult();
    }
}
