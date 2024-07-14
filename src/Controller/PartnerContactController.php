<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Entity\PartnerContact;
use App\Form\Type\PartnerContactType;
use App\Repository\PartnerContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class PartnerContactController extends AbstractController
{
    #[Route('/partner/{id}/contact', name: 'app_partner_contact_index')]
    public function index(
        Partner $partner,
        PaginatorInterface $paginator,
        PartnerContactRepository $partnerContactRepository,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] string $sort = 'createdAt',
        #[MapQueryParameter] string $sortDirection = 'DESC',
        #[MapQueryParameter('query')] string $query = null,
    ): Response {
        $validSorts = ['firstname', 'lastname', 'createdAt'];
        $sort = in_array($sort, $validSorts) ? $sort : 'createdAt';

        $pagination = $paginator->paginate(
            $partnerContactRepository->findBySearchQueryBuilder($partner, $query, $sort, $sortDirection),
            $page,
            15
        );

        return $this->render('partner_contact/index.html.twig', [
            'partner' => $partner,
            'contacts' => $pagination,
            'sort' => $sort,
            'sortDirection' => $sortDirection
        ]);
    }

    #[Route('/partner/{id}/contact/create', name: 'app_partner_contact_create')]
    public function create(Request $request, Partner $partner, EntityManagerInterface $entityManager): Response
    {
        $partnerContact = new PartnerContact();
        $form = $this->createPartnerContactForm($partnerContact, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partnerContact->setPartner($partner);
            $entityManager->persist($partnerContact);
            $entityManager->flush();

            $this->addFlash('success', 'Contact créé');

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('partner_contact/create.html.twig', 'stream_response', [
                    'contact' => $partnerContact
                ]);
            }

            return $this->redirectToRoute('app_partner_contact_index', [
                'id' => $partner->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('partner_contact/create.html.twig', [
            'form' => $form,
            'contact' => $partnerContact,
            'partner' => $partner
        ]);
    }

    #[Route('/partner/contact/{id}/update', name: 'app_partner_contact_update')]
    public function update(
        Request $request,
        PartnerContact $partnerContact,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createPartnerContactForm($partnerContact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Contact modifié');

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('partner_contact/update.html.twig', 'stream_response', [
                    'contact' => $partnerContact
                ]);
            }

            return $this->redirectToRoute('app_partner_contact_index', [
                'id' => $partnerContact->getPartner()->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('partner_contact/update.html.twig', [
            'form' => $form,
            'contact' => $partnerContact,
            'partner' => null
        ]);
    }

    #[Route('/partner/contact/{id}/delete', name: 'app_partner_contact_delete')]
    public function delete(
        Request $request,
        PartnerContact $partnerContact,
        EntityManagerInterface $entityManager
    ): Response {
        $id = $partnerContact->getId();

        if ($this->isCsrfTokenValid('delete'.$partnerContact->getId(), $request->request->get('_token'))) {
            $entityManager->remove($partnerContact);
            $entityManager->flush();

            $this->addFlash('success', 'Contact supprimé');

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('partner_contact/delete.html.twig', 'success_stream', [
                    'id' => $id,
                ]);
            }
        }

        return $this->redirectToRoute('app_client_contact_index', [
            'id' => $partnerContact->getPartner()->getId()
        ], Response::HTTP_SEE_OTHER);
    }

    private function createPartnerContactForm(
        ?PartnerContact $partnerContact = null,
        ?Partner $partner = null
    ): FormInterface {
        $partnerContact = $partnerContact ?? new PartnerContact();

        return $this->createForm(PartnerContactType::class, $partnerContact, [
            'action' => $partnerContact->getId()
                ? $this->generateUrl('app_partner_contact_update', ['id' => $partnerContact->getId()])
                : $this->generateUrl('app_partner_contact_create', ['id' => $partner->getId()]),
        ]);
    }
}