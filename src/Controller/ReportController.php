<?php

namespace App\Controller;

use App\Entity\ClientCase;
use App\Entity\Report;
use App\Entity\ReportStatus;
use App\Entity\ReportType;
use App\Entity\User;
use App\Repository\ReportRepository;
use App\Repository\ReportStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ReportController extends AbstractController
{
    #[Route('/client-case/{id}/report/{reportType}/create', name: 'app_report_create')]
    public function create(
        ClientCase $clientCase,
        ReportType $reportType,
        ReportRepository $reportRepository,
        ReportStatusRepository $reportStatusRepository,
        EntityManagerInterface $entityManager,
        #[CurrentUser] User $user
    ): Response {
        $count = $reportRepository->findCountDraftByClientCase($clientCase);

        if ($count > 0) {
            return new JsonResponse([
                'url' => $this->generateUrl('app_report_create', [
                    'id' => $clientCase->getId(),
                    'reportType' => $reportType->getId()
                ])
            ], 400);
        }

        if (in_array($reportType->getCode(), ['AD', 'FCE'])) {
            $report = new Report();
            $status = $reportStatusRepository->findOneBy(['code' => 'draft']);
            $lastReport = $reportRepository->findLastReportByTypeAndClientCase($reportType, $clientCase);
            $number = $lastReport ? $lastReport->getNumber() + 1 : 1;

            $report
                ->setReportType($reportType)
                ->setCreatedBy($user)
                ->setReportStatus($status)
                ->setNumber($number)
                ->setReference($reportType->getCode() . '-' . sprintf("%03s", $number))
                ->setClientCase($clientCase);

            $entityManager->persist($report);
            $entityManager->flush();
        }


        return new JsonResponse([
            'url' => $this->generateUrl('app_report_index', ['id' => $clientCase->getId()])
        ], 200);
    }

    #[Route('/client-case/{id}/report', name: 'app_report_index')]
    public function index(): Response
    {
        return $this->render('report/index.html.twig');
    }
}