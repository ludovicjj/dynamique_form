<?php

namespace App\Repository;

use App\Entity\Partner;
use App\Entity\PartnerContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PartnerContact>
 */
class PartnerContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PartnerContact::class);
    }

    public function findBySearchQueryBuilder(
        Partner $partner,
        ?string $query,
        ?string $sort,
        string $direction = 'DESC'
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('pc');

        $subQuery = $this->getEntityManager()->createQueryBuilder()
            ->select('pc_2.id')
            ->from(PartnerContact::class, 'pc_2')
            ->orWhere($qb->expr()->like('pc_2.firstname', ':query'))
            ->orWhere($qb->expr()->like('pc_2.lastname', ':query'))
            ->getDQL();


        $qb
            ->andWhere('pc.partner = :partner')
            ->setParameter('partner', $partner);

        if ($query) {
            $qb
                ->andWhere($qb->expr()->in('pc.id', $subQuery))
                ->setParameter('query', '%' . $query . '%');
        }

        if ($sort) {
            $qb->orderBy('pc.' . $sort, $direction);
        }

        return $qb;
    }
}
