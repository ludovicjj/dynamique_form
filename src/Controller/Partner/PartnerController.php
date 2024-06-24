<?php

namespace App\Controller\Partner;

use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PartnerController extends AbstractController
{
    #[Route('/partner', name: 'app_partner')]
    public function index(CountryRepository $countryRepository): Response
    {
        return $this->render('partner/index.html.twig', [
            'countries' => $countryRepository->findAll()
        ]);
    }
}