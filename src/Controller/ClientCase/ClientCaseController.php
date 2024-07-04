<?php

namespace App\Controller\ClientCase;

use App\Entity\ClientCase;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
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

    #[Route('/client-case/{id}', name: "app_client_case_show")]
    public function show(
        #[MapEntity(expr: 'repository.findClientCaseShow(id)')]
        ClientCase $clientCase
    ): Response
    {
        return $this->render('client_case/show.html.twig', [
            'client_case' => $clientCase
        ]);
    }
}