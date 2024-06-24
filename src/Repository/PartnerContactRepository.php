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

    public function findByPartnerQueryBuilder(Partner $partner): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('partner_contact');

        $queryBuilder
            ->andWhere('partner_contact.partner = :partner')
            ->setParameter('partner', $partner)
            ->orderBy('partner_contact.firstname', 'ASC');

        return $queryBuilder;
    }

    public function searchPaginated(?Partner $partner): array
    {
        if (!$partner) {
            return [];
        }

        $queryBuilder = $this->createQueryBuilder('partner_contact');

        $queryBuilder
            ->andWhere('partner_contact.partner = :partner')
            ->setParameter('partner', $partner);

        return $queryBuilder->getQuery()->getResult();
    }
}
