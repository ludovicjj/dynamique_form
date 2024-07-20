<?php

namespace App\Service;

use App\Entity\ClientCase;
use App\Entity\Report;
use App\Entity\ReportType;
use App\Repository\ReportRepository;
use App\Repository\ReportStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReportService
{
    public function __construct(
        private readonly ReportStatusRepository $reportStatusRepository,
        private readonly ReportRepository $reportRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    public function create(ClientCase $clientCase, ReportType $reportType): Report
    {
        $report = new Report();
        $user = $this->tokenStorage->getToken()->getUser();

        if (in_array($reportType->getCode(), ['AD', 'FCE', 'SAv'])) {
            $status = $this->reportStatusRepository->findOneBy(['code' => 'draft']);
            $lastReport = $this->reportRepository->findLastReportByTypeAndClientCase($reportType, $clientCase);
            $number = $lastReport ? $lastReport->getNumber() + 1 : 1;

            $report
                ->setReportType($reportType)
                ->setCreatedBy($user)
                ->setReportStatus($status)
                ->setNumber($number)
                ->setReference($reportType->getCode() . '-' . sprintf("%03s", $number))
                ->setClientCase($clientCase);

            $this->entityManager->persist($report);
            $this->entityManager->flush();
        }

        return $report;
    }
}