<?php

namespace App\Repository;

use App\Entity\ClientCase;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    public function findBySearchQueryBuilder(
        ?string $query,
        ?int $userId,
        ?string $sort,
        string $direction = 'DESC'
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('cc');

        $qb
            ->leftJoin('cc.collaborators', 'collaborator')
            ->addSelect('collaborator');

        $subQuery = $this->getEntityManager()->createQueryBuilder()
            ->select('cc_2.id')
            ->from(ClientCase::class, 'cc_2')
            ->orWhere($qb->expr()->like('cc_2.reference', ':query'))
            ->orWhere($qb->expr()->like('cc_2.projectName', ':query'))
            ->getDQL();

        $collaboratorSubQuery = $this->getEntityManager()->createQueryBuilder()
            ->select('cc_3.id')
            ->from(ClientCase::class, 'cc_3')
            ->leftJoin('cc_3.collaborators', 'sub_collaborator')
            ->orWhere($qb->expr()->eq('cc_3.manager', ':user_id'))
            ->orWhere($qb->expr()->eq('sub_collaborator', ':user_id'))
            ->getDQL();


        if ($query) {
            $qb
                ->andWhere($qb->expr()->in('cc.id', $subQuery))
                ->setParameter('query', '%' . $query . '%');
        }

        if ($userId) {
            if ($userId === -1) {
                $qb->andWhere($qb->expr()->isNull('cc.manager'));
            } else {
                $qb
                    ->andWhere($qb->expr()->in('cc.id', $collaboratorSubQuery))
                    ->setParameter('user_id', $userId);
            }
        }

        if ($sort) {
            $qb->orderBy('cc.' . $sort, $direction);
        }

        return $qb;
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
