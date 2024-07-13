<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PartnerController extends AbstractController
{
    #[Route('/partner', name: 'app_partner_index')]
    public function index(): Response
    {
        return $this->render('partner/index.html.twig');
    }
}