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

    public function search(string $query): array
    {
        $queryBuilder = $this->createQueryBuilder('cc');

        if ($query) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('cc.projectName', ':project_name'))
                ->setParameter('project_name', "%".$query."%");
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
