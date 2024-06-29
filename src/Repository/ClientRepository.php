<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function searchPaginated(int $page, int $itemPerPage, ?int $country = null): array
    {
        $offset = $page * $itemPerPage;
        $queryBuilder = $this->createQueryBuilder('client');

        $queryBuilder
            ->leftJoin('client.country', 'country')
            ->addSelect('country')
            ->addOrderBy('client.createdAt', 'DESC')
            ->setMaxResults($offset);

        if ($country) {
            $queryBuilder
                ->andWhere('country = :country')
                ->setParameter('country', $country);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
