<?php

namespace App\Repository;

use App\Entity\ClientCase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientCase>
 */
class ClientCaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientCase::class);
    }

    public function searchPaginated(string $query, int $page, int $itemPerPage): array
    {
        $offset = $page * $itemPerPage;

        $queryBuilder = $this->createQueryBuilder('cc');

        if ($query) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('cc.projectName', ':project_name'))
                ->setParameter('project_name', "%".$query."%");
        }

        $queryBuilder
            ->setMaxResults($offset)
            ->addOrderBy('cc.signedAt', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function clientCaseCount(string $query): int
    {
        $queryBuilder = $this->createQueryBuilder('cc');
        $queryBuilder->select('COUNT(cc)');

        if ($query) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('cc.projectName', ':project_name'))
                ->setParameter('project_name', "%".$query."%");
        }

        return $queryBuilder->getQuery()->getSingleScalarResult() ?? 0;
    }
}
