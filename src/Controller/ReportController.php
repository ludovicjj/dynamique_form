<?php

namespace App\Controller;

use App\Entity\ClientCase;
use App\Entity\Report;
use App\Entity\ReportType;
use App\Repository\ReportRepository;
use App\Service\ReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class ReportController extends AbstractController
{
    #[Route('/client-case/{id}/report/{reportType}/create', name: 'app_report_create')]
    public function create(
        Request $request,
        ClientCase $clientCase,
        ReportType $reportType,
        ReportRepository $reportRepository,
        ReportService $reportService,
        #[MapQueryParameter] bool $confirm = false
    ): Response {
        if ($request->headers->has('turbo-frame')) {
            $count = $reportRepository->findCountDraftByTypeAndClientCase($reportType, $clientCase);

            if ($count > 0 && !$confirm) {
                return new JsonResponse([
                    'url' => $this->generateUrl('app_report_create', [
                        'id' => $clientCase->getId(),
                        'reportType' => $reportType->getId(),
                        'confirm' => true
                    ])
                ], Response::HTTP_BAD_REQUEST);
            }

            $report = $reportService->create($clientCase, $reportType);
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

            return $this->renderBlock('report/create.html.twig', 'stream_response', [
                'report' => $report,
                'reportType' => $reportType
            ]);
        }

        $report = $reportService->create($clientCase, $reportType);
        return $this->redirectToRoute('app_client_case_show', ['id' => $clientCase->getId()]);
    }

    #[Route('/client-case/{id}/report/{report}', name: 'app_report_index')]
    public function index(
        ClientCase $clientCase,
        Report $report
    ): Response
    {
        return $this->render('report/index.html.twig', [
            'clientCase' => $clientCase,
            'report' => $report
        ]);
    }
}