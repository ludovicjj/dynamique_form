<?php

namespace App\Controller;

use App\Entity\ClientCase;
use App\Entity\User;
use App\Form\Type\ClientCaseDocumentType;
use App\Form\Type\ClientCaseType;
use App\Repository\ClientCaseRepository;
use App\Repository\ClientCaseStatusRepository;
use App\Repository\UserRepository;
use App\Service\DocumentService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\UX\Turbo\TurboBundle;

class ClientCaseController extends AbstractController
{
    #[Route('/client-case', name: "app_client_case_index")]
    public function index(
        ClientCaseRepository $clientCaseRepository,
        UserRepository $userRepository,
        PaginatorInterface $paginator,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] string $sort = 'createdAt',
        #[MapQueryParameter] string $sortDirection = 'DESC',
        #[MapQueryParameter('query')] string $query = null,
        #[MapQueryParameter('collaborator')] string $user = null,
    ): Response{
        $validSorts = ['reference', 'name', 'createdAt'];
        $sort = in_array($sort, $validSorts) ? $sort : 'createdAt';

        $userId = (int)$user;

        $pagination = $paginator->paginate(
            $clientCaseRepository->findBySearchQueryBuilder($query, $userId, $sort, $sortDirection),
            $page,
            15
        );

        return $this->render('client_case/index.html.twig', [
            'collaborators' => $userRepository->findAll(),
            'clientCases' => $pagination,
            'sort' => $sort,
            'sortDirection' => $sortDirection
        ]);
    }

    #[Route('/client-case/create', name: "app_client_case_create")]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        #[CurrentUser] User $user,
        ClientCaseStatusRepository $clientCaseStatusRepository
    ): Response {
        $clientCase = new ClientCase();
        $form = $this->createClientCaseForm($clientCase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $status = $clientCaseStatusRepository->find(1);

            $clientCase
                ->setClientCaseStatus($status)
                ->setCreatedBy($user);

            $entityManager->persist($clientCase);
            $entityManager->flush();

            $this->addFlash('success', 'Affaire créée');

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('client_case/create.html.twig', 'stream_response', [
                    'clientCase' => $clientCase
                ]);
            }
        }

        return $this->render('client_case/create.html.twig', [
           'form' => $form,
            'clientCase' => $clientCase
        ]);
    }

    #[Route('/client-case/{id}/update', name: "app_client_case_update")]
    public function update(Request $request, ClientCase $clientCase, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createClientCaseForm($clientCase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Affaire modifiée');

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('client_case/update.html.twig', 'stream_response', [
                    'clientCase' => $clientCase
                ]);
            }
        }

        return $this->render('client_case/update.html.twig', [
            'form' => $form,
            'clientCase' => $clientCase
        ]);
    }

    #[Route('/client-case/{id}/delete', name: "app_client_case_delete")]
    public function delete(Request $request, ClientCase $clientCase, EntityManagerInterface $entityManager): Response
    {
        $id = $clientCase->getId();
        if ($this->isCsrfTokenValid('delete'.$clientCase->getId(), $request->request->get('_token'))) {
            $entityManager->remove($clientCase);
            $entityManager->flush();

            $this->addFlash('success', 'Affaire supprimée');

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('client_case/delete.html.twig', 'success_stream', [
                    'id' => $id,
                ]);
            }
        }

        return $this->redirectToRoute('app_client_case_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/client-case/{id}', name: "app_client_case_show")]
    public function show(
        #[MapEntity(expr: 'repository.findClientCaseShow(id)')]
        ClientCase $clientCase
    ): Response
    {
        return $this->render('client_case/show.html.twig', [
            'clientCase' => $clientCase
        ]);
    }

    #[Route('/client-case/{id}/document', name: "app_client_case_document")]
    public function document(
        Request $request,
        ClientCase $clientCase,
        DocumentService $documentService,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(ClientCaseDocumentType::class, $clientCase, [
            'action' => $this->generateUrl('app_client_case_document', ['id' => $clientCase->getId()])
        ]);

        $form->handleRequest($request);

        $documentData = $documentService->getDocumentData($clientCase);

        // TODO constraint name
        if ($form->isSubmitted() && $form->isValid()) {
            $files = $request->files->get('client_case_document', [])['files'] ?? [];
            $documentService->buildAndPersist($form, $clientCase, $files);

            if ($documentService->hasChangeSet($clientCase, $entityManager)) {
                $this->addFlash('success', 'Documents modifiés');
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_client_case_show', [
                'id' => $clientCase->getId()
            ]);
        }

        return $this->render('client_case/document.html.twig', [
            'form' => $form,
            'clientCase' => $clientCase,
            'documentData' => $documentData
        ]);
    }

    private function createClientCaseForm(?ClientCase $clientCase = null): FormInterface
    {
        $clientCase = $clientCase ?? new ClientCase();

        return $this->createForm(ClientCaseType::class, $clientCase, [
            'action' => $clientCase->getId()
                ? $this->generateUrl('app_client_case_update', ['id' => $clientCase->getId()])
                : $this->generateUrl('app_client_case_create')
        ]);
    }
}