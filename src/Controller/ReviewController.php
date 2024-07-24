<?php

namespace App\Controller;

use App\Entity\Report;
use App\Entity\Review;
use App\Form\Type\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReviewController extends AbstractController
{
    #[Route('/report/{id}/review/create', name: 'app_review_create')]
    public function create(
        Request $request,
        Report $report,
        EntityManagerInterface $entityManager
    ): Response {
        $review = new Review();
        $form = $this->createReviewForm($report, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO create review
            dd($review);
        }

        return $this->render('review/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/report/{id}/review/{review}/update', name: 'app_review_update')]
    public function update(
        Request $request,
        Report $report,
        Review $review,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createReviewForm($report, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        }

        return $this->render('review/update.html.twig', [
            'form' => $form,
        ]);
    }

    private function createReviewForm(Report $report, ?Review $review = null)
    {
        $review = $review ?? new Review();

        return $this->createForm(
            ReviewType::class,
            $review,
            [
                'action' => $review->getId()
                    ? $this->generateUrl('app_review_update', ['id' => $report->getId(), 'review' => $review->getId()])
                    : $this->generateUrl('app_review_create', ['id' => $report->getId()]),
                'report' => $report
            ],
        );
    }
}