<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\ClientContact;
use App\Form\Type\ClientContactType;
use App\Repository\ClientContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class ClientContactController extends AbstractController
{
    #[Route('/client/{id}/contact', name: 'app_client_contact_index')]
    public function index(
        Client $client,
        ClientContactRepository $clientContactRepository,
        PaginatorInterface $paginator,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] string $sort = 'createdAt',
        #[MapQueryParameter] string $sortDirection = 'DESC',
        #[MapQueryParameter('query')] string $query = null,
    ): Response {
        $pagination = $paginator->paginate(
            $clientContactRepository->findBySearchQueryBuilder($client, $query, $sort, $sortDirection),
            $page,
            15
        );

        return $this->render('client_contact/index.html.twig', [
            'client' => $client,
            'contacts' => $pagination,
            'sort' => $sort,
            'sortDirection' => $sortDirection
        ]);
    }

    #[Route('/client/{id}/contact/create', name: 'app_client_contact_create', methods: ['GET', 'POST'])]
    public function create(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $clientContact = new ClientContact();

        $form = $this->createContactForm($clientContact, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientContact->setClient($client);
            $entityManager->persist($clientContact);
            $entityManager->flush();

            $this->addFlash('success', 'Contact créé');

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('client_contact/create.html.twig', 'stream_response', [
                    'contact' => $clientContact
                ]);
            }

            return $this->redirectToRoute('app_client_contact_index', [
                'id' => $client
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client_contact/create.html.twig', [
            'contact' => $clientContact,
            'form' => $form,
            'client' => $client
        ]);
    }

    #[Route('/client/contact/{id}/update', name: 'app_client_contact_update', methods: ['GET', 'POST'])]
    public function update(
        Request $request,
        ClientContact $clientContact,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createContactForm($clientContact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Client modifié');

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('client_contact/update.html.twig', 'stream_response', [
                    'contact' => $clientContact
                ]);
            }

            return $this->redirectToRoute('app_client_contact_index', [
                'id' => $clientContact->getClient()->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client_contact/update.html.twig', [
            'contact' => $clientContact,
            'form' => $form,
            'client' => null
        ]);
    }

    #[Route('/client/contact/{id}/delete', name: 'app_client_contact_delete', methods: ['GET', 'POST'])]
    public function delete(
        Request $request,
        ClientContact $clientContact,
        EntityManagerInterface $entityManager
    ): Response {
        $id = $clientContact->getId();

        if ($this->isCsrfTokenValid('delete'.$clientContact->getId(), $request->request->get('_token'))) {
            $entityManager->remove($clientContact);
            $entityManager->flush();

            $this->addFlash('success', 'Contact supprimé');

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('client_contact/delete.html.twig', 'success_stream', [
                    'id' => $id,
                ]);
            }
        }

        return $this->redirectToRoute('app_client_contact_index', [
            'id' => $clientContact->getClient()->getId()
        ], Response::HTTP_SEE_OTHER);
    }

    private function createContactForm(ClientContact $clientContact = null, Client $client = null): FormInterface
    {
        $clientContact = $clientContact ?? new ClientContact();

        return $this->createForm(ClientContactType::class, $clientContact, [
            'action' => $clientContact->getId()
                ? $this->generateUrl('app_client_contact_update', ['id' => $clientContact->getId()])
                : $this->generateUrl('app_client_contact_create', ['id' => $client->getId()]),
        ]);
    }
}