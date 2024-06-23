<?php

namespace App\Controller\Debug;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DebugController extends AbstractController
{
    #[Route('/debug' , name: 'app_debug')]
    public function index(): Response
    {
        return $this->render('debug/index.html.twig');
    }


    #[Route('/debug/download' , name: 'app_debug_download')]
    public function download(): Response
    {
        return new JsonResponse([
            'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Shadow_2752.jpg/250px-Shadow_2752.jpg'
        ]);
    }
}