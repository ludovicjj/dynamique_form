<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\Type\ClientType;
use App\Repository\ClientRepository;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client_index')]
    public function index(CountryRepository $countryRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'countries' => $countryRepository->findAll()
        ]);
    }

    #[Route('/client/listing', name: 'app_client_list', methods: ['GET', 'POST'])]
    public function listing(
        Request $request,
        ClientRepository $clientRepository,
        PaginatorInterface $paginator,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] string $sort = 'createdAt',
        #[MapQueryParameter] string $sortDirection = 'ASC',
        #[MapQueryParameter('query')] string $query = null,
        #[MapQueryParameter('country')] string $country = null
    ): Response {
        // prevent user redirect to this url if refresh page after begin search data
        if (!$request->headers->has('turbo-frame')) {
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        $validSorts = ['companyName', 'createdAt'];
        $sort = in_array($sort, $validSorts) ? $sort : 'createdAt';

        $country = (int)$country > 0 ? $country : null;

        $clientPaginated = $paginator->paginate(
            $clientRepository->findBySearchQueryBuilder($query, $country, $sort, $sortDirection),
            $page,
            5
        );

        return $this->render('client/_list.html.twig', [
            'clients' => $clientPaginated,
            'sort' => $sort,
            'sortDirection' => $sortDirection
        ]);
    }

    #[Route('/client/create', name: 'app_client_create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // prevent open in new tab
        if (!$request->headers->has('turbo-frame')) {
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        $client = new Client();
        $form = $this->createClientForm($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'Client crée');

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('client/create.html.twig', 'stream_response', [
                    'client' => $client
                ]);
            }

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/create.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/client/{id}/update', name: 'app_client_update', methods: ['GET', 'POST'])]
    public function update(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createClientForm($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'Client modifié');

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/update.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    private function createClientForm(Client $client = null): FormInterface
    {
        $client = $client ?? new Client();

        return $this->createForm(ClientType::class, $client, [
            'action' => $client->getId()
                ? $this->generateUrl('app_client_update', ['id' => $client->getId()])
                : $this->generateUrl('app_client_create'),
        ]);
    }
}