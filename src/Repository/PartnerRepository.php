<?php

namespace App\Repository;

use App\Entity\Partner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Partner>
 */
class PartnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partner::class);
    }

    public function findBySearchQueryBuilder(
        ?string $query,
        ?int $country,
        ?string $sort,
        string $direction = 'DESC'
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('p');

        if ($query) {
            $qb
                ->andWhere('p.companyName LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        if ($country) {
            $qb
                ->andWhere('p.country = :country')
                ->setParameter('country', $country);
        }

        if ($sort) {
            $qb->orderBy('p.' . $sort, $direction);
        }

        return $qb;
    }
}
