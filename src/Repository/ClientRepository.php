<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findBySearchQueryBuilder(
        ?string $query,
        ?int $country,
        ?string $sort,
        string $direction = 'DESC'
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('c');

        if ($query) {
            $qb
                ->andWhere('c.companyName LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        if ($country) {
            $qb
                ->andWhere('c.country = :country')
                ->setParameter('country', $country);
        }

        if ($sort) {
            $qb->orderBy('c.' . $sort, $direction);
        }

        return $qb;
    }
}
