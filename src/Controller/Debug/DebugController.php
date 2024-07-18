<?php

namespace App\Controller\Debug;

use App\Repository\ClientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
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

    #[Route('/debug/test' , name: 'app_debug_test')]
    public function test(
        PaginatorInterface $paginator,
        ClientRepository $clientRepository,
        #[MapQueryParameter] int $page = 1,
    ): Response {
        $pagination = $paginator->paginate(
            $clientRepository->findBySearchQueryBuilder(null, null, null),
            $page,
            1
        );

        return $this->render('debug/test.html.twig', [
            'clients' => $pagination
        ]);
    }
}