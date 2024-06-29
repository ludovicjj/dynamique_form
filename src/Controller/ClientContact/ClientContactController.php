<?php

namespace App\Controller\ClientContact;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientContactController extends AbstractController
{
    #[Route('/client/{id}/contact', name: 'app_client_contact')]
    public function index(Client $client): Response
    {
        return $this->render('client_contact/index.html.twig', [
            'client' => $client
        ]);
    }
}