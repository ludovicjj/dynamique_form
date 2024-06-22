<?php

namespace App\Controller\ClientCase;

use App\Entity\ClientCase;
use App\Form\Type\ClientCaseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientCaseController extends AbstractController
{
    #[Route('/client-case', name: "app_client_case")]
    public function index(): Response
    {
        return $this->render('client_case/index.html.twig');
    }

    #[Route('/client-case/{id}/edit', name: "app_client_case_edit")]
    public function edit(
        ClientCase $clientCase,
        Request $request
    ): Response {
        $form = $this->createForm(ClientCaseType::class, $clientCase)->handleRequest($request);

        return $this->render('client_case/edit.html.twig', [
            'form' => $form,
            'id' => $clientCase->getId()
        ]);
    }
}