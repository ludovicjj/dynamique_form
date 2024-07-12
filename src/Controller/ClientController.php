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
    public function index(
        CountryRepository $countryRepository,
        ClientRepository $clientRepository,
        PaginatorInterface $paginator,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] string $sort = 'createdAt',
        #[MapQueryParameter] string $sortDirection = 'DESC',
        #[MapQueryParameter('query')] string $query = null,
        #[MapQueryParameter('country')] string $country = null
    ): Response {
        $validSorts = ['companyName', 'createdAt'];
        $sort = in_array($sort, $validSorts) ? $sort : 'createdAt';

        $country = (int)$country > 0 ? $country : null;

        $clientPaginated = $paginator->paginate(
            $clientRepository->findBySearchQueryBuilder($query, $country, $sort, $sortDirection),
            $page,
            6
        );

        return $this->render('client/index.html.twig', [
            'clients' => $clientPaginated,
            'sort' => $sort,
            'sortDirection' => $sortDirection,
            'countries' => $countryRepository->findAll()
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

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('client/update.html.twig', 'stream_response', [
                    'client' => $client
                ]);
            }

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/update.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/client/{id}/delete', name: 'app_client_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $id = $client->getId();
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();

            $this->addFlash('success', 'Client supprimé');

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('client/delete.html.twig', 'success_stream', [
                    'id' => $id,
                ]);
            }
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
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