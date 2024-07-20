<?php

namespace App\Repository;

use App\Entity\ClientCase;
use App\Entity\Report;
use App\Entity\ReportType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Report>
 */
class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    public function findCountDraftByClientCase(ClientCase $clientCase): int
    {
        $qb = $this->createQueryBuilder('r');

        $qb
            ->select('COUNT(r.id)')

            ->leftJoin('r.reportStatus', 'rs')
            ->andWhere('rs.code = :code')
            ->setParameter('code', 'draft')

            ->leftJoin('r.clientCase', 'cc')
            ->andWhere('cc = :client_case')
            ->setParameter('client_case', $clientCase);

        return (int)$qb->getQuery()->getSingleScalarResult() ?? 0;
    }

    public function findLastReportByTypeAndClientCase(ReportType $reportType, ClientCase $clientCase): ?Report
    {
        $qb = $this->createQueryBuilder('r');

        $qb
            ->leftJoin('r.reportType', 'rt')
            ->andWhere('rt = :report_type')
            ->setParameter('report_type', $reportType)

            ->leftJoin('r.clientCase', 'cc')
            ->andWhere('cc = :client_case')
            ->setParameter('client_case', $clientCase)

            ->orderBy("r.number", "DESC")
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
