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

        $queryBuilder = $this->createQueryBuilder('client_case');

        if ($query) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('client_case.projectName', ':project_name'))
                ->setParameter('project_name', "%".$query."%");
        }

        $queryBuilder
            ->setMaxResults($offset)
            ->addOrderBy('client_case.id', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function clientCaseCount(string $query): int
    {
        $queryBuilder = $this->createQueryBuilder('client_case');
        $queryBuilder->select('COUNT(client_case)');

        if ($query) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('client_case.projectName', ':project_name'))
                ->setParameter('project_name', "%".$query."%");
        }

        return $queryBuilder->getQuery()->getSingleScalarResult() ?? 0;
    }

    public function findClientCaseShow(int $id): ?ClientCase
    {
        $queryBuilder = $this->createQueryBuilder('client_case');

        $queryBuilder
            ->leftJoin('client_case.partnerContacts', 'partner_contact')
            ->addSelect('partner_contact')

            ->leftJoin('client_case.documents', 'document')
            ->addSelect('document')

            ->leftJoin('partner_contact.partner', 'partner')
            ->addSelect('partner')

            ->leftJoin('client_case.client', 'client')
            ->addSelect('client')

            ->leftJoin('client_case.clientContacts', 'client_contact')
            ->addSelect('client_contact')

            ->leftJoin('client_case.missions', 'mission')
            ->addSelect('mission')

            ->andWhere('client_case.id = :id')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
