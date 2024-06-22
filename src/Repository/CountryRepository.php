<?php

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Country>
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    public function findAllOrderByNameQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('c');

        $queryBuilder
            ->orderBy('c.name', 'ASC');

        return $queryBuilder;
    }
}
