<?php

namespace App\Repository;

use App\Entity\Partner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function searchPaginated(int $page, int $itemPerPage): array
    {
        $offset = $page * $itemPerPage;

        $queryBuilder = $this->createQueryBuilder('partner');

        $queryBuilder
            ->leftJoin('partner.country', 'country')
            ->addSelect('country')
            ->setMaxResults($offset);

        return $queryBuilder->getQuery()->getResult();
    }
}
