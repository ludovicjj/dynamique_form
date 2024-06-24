<?php

namespace App\Controller\PartnerContact;

use App\Entity\Partner;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PartnerContactController extends AbstractController
{
    #[Route('/partner/{id}/contact', name: 'app_partner_contact')]
    public function index(Partner $partner): Response
    {
        return $this->render('partner_contact/index.html.twig', [
            'partner' => $partner
        ]);
    }
}