<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Form\Type\PartnerType;
use App\Repository\CountryRepository;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class PartnerController extends AbstractController
{
    #[Route('/partner', name: 'app_partner_index')]
    public function index(
        PartnerRepository $partnerRepository,
        CountryRepository $countryRepository,
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

        $pagination = $paginator->paginate(
            $partnerRepository->findBySearchQueryBuilder($query, $country, $sort, $sortDirection),
            $page,
            15
        );

        return $this->render('partner/index.html.twig', [
            'countries' => $countryRepository->findAll(),
            'partners' => $pagination,
            'sort' => $sort,
            'sortDirection' => $sortDirection
        ]);
    }

    #[Route('/partner/create', name: 'app_partner_create')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $partner = new Partner();

        $form = $this->createPartnerForm($partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($partner);
            $entityManager->flush();

            $this->addFlash('success', 'Partenaire créé');

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('partner/create.html.twig', 'stream_response', [
                    'partner' => $partner
                ]);
            }

            return $this->redirectToRoute('app_partner_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('partner/create.html.twig', [
            'partner' => $partner,
            'form' => $form,
        ]);
    }

    #[Route('/partner/{id}/update', name: 'app_partner_update')]
    public function update(
        Request $request,
        Partner $partner,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createPartnerForm($partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Partenaire modifié');

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('partner/update.html.twig', 'stream_response', [
                    'partner' => $partner
                ]);
            }

            return $this->redirectToRoute('app_partner_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('partner/update.html.twig', [
            'partner' => $partner,
            'form' => $form,
        ]);
    }

    #[Route('/partner/{id}/delete', name: 'app_partner_delete')]
    public function delete(
        Request $request,
        Partner $partner,
        EntityManagerInterface $entityManager
    ): Response {
        $id = $partner->getId();
        if ($this->isCsrfTokenValid('delete'.$partner->getId(), $request->request->get('_token'))) {
            $entityManager->remove($partner);
            $entityManager->flush();

            $this->addFlash('success', 'Partenaire supprimé');

            if ($request->headers->has('turbo-frame')) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('partner/delete.html.twig', 'success_stream', [
                    'id' => $id,
                ]);
            }
        }

        return $this->redirectToRoute('app_partner_index', [], Response::HTTP_SEE_OTHER);
    }

    private function createPartnerForm(Partner $partner = null): FormInterface
    {
        $partner = $partner ?? new Partner();

        return $this->createForm(PartnerType::class, $partner, [
            'action' => $partner->getId()
                ? $this->generateUrl('app_partner_update', ['id' => $partner->getId()])
                : $this->generateUrl('app_partner_create'),
        ]);
    }
}