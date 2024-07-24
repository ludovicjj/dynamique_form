<?php

namespace App\Controller;

use App\Repository\ClientCaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app', methods: 'GET')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route('/home', name: 'app_home')]
    public function home(ClientCaseRepository $clientCaseRepository): Response
    {
        $clientCase = $clientCaseRepository->find(4);
        return $this->render('home.html.twig', [
            'reports' => $clientCase->getReports()
        ]);
    }

    #[Route('/articles', name: 'article_list')]
    public function list(ClientCaseRepository $clientCaseRepository): Response
    {
        $clientCase = $clientCaseRepository->find(4);
        return $this->render('home.html.twig', [
            'reports' => $clientCase->getReports()
        ]);
    }
}