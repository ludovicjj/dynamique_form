<?php

namespace App\Controller\ClientCase;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientCaseController extends AbstractController
{
    #[Route('/client-case', name: "app_client_case")]
    public function index(): Response
    {
        return $this->render('client_case/index.html.twig');
    }
}