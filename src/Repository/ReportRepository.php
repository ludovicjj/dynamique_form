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

    public function findCountDraftByTypeAndClientCase(ReportType $reportType, ClientCase $clientCase): int
    {
        $qb = $this->createQueryBuilder('r');

        $qb
            ->select('COUNT(r.id)')

            ->leftJoin('r.reportStatus', 'rs')
            ->andWhere('rs.code = :code')
            ->setParameter('code', 'draft')

            ->leftJoin('r.reportType', 'rt')
            ->andWhere('rt = :report_type')
            ->setParameter('report_type', $reportType)

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

    public function findGroupedReports(ClientCase $clientCase): array
    {
        $qb = $this->createQueryBuilder('r');

        $qb
            ->select('r AS report', 'rt',
                "(CASE 
                    WHEN rt.code IN ('AD', 'FCE', 'SAv') THEN 'construction' 
                    WHEN rt.code = 'RFCT' THEN 'handover' 
                    ELSE 'OTHER' 
                END) AS groupKey"
            )
            ->leftJoin('r.reportType', 'rt')
            ->andWhere('r.clientCase = :client_case')
            ->setParameter('client_case', $clientCase)
            ->orderBy('groupKey', 'ASC');

        $reports = $qb->getQuery()->getResult();

        $groupedReports = [
            'construction' => [],
            'handover' => [],
            'design' => []
        ];

        foreach ($reports as $report) {
            $groupKey = $report['groupKey'];
            $groupedReports[$groupKey][] = $report["report"];
        }

        return $groupedReports;
    }
}
