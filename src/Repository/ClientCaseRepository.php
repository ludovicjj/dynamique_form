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

    // Query
    public function searchPaginated(string $query): array
    {
        $queryBuilder = $this->createQueryBuilder('cc');

        if ($query) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('cc.projectName', ':project_name'))
                ->setParameter('project_name', "%".$query."%");
        }

        $queryBuilder
            ->setMaxResults(ClientCase::ITEMS_PER_PAGE)
            ->addOrderBy('cc.signedAt', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }
}
