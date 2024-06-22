<?php

namespace App\Twig\Components\ClientCase;

use App\Entity\ClientCase;
use App\Repository\ClientCaseRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ClientCaseSearch extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    public string $filter = '';

    #[LiveProp(writable: true)]
    public string $query = '';

    public bool $isCreated = false;

    public string $message = '';

    public function __construct(
        private readonly ClientCaseRepository $clientCaseRepository,
        private readonly RequestStack $requestStack,
        private readonly PaginatorInterface $paginator
    )
    {
    }

    public function getClientCases(): PaginationInterface
    {
        $request = $this->requestStack->getMainRequest();
        $page = max($request->query->get('page', 1), 1);

        $query = $this->clientCaseRepository->searchPaginated($this->filter);
        return $this->paginator->paginate($query, $page, ClientCase::ITEMS_PER_PAGE);
    }

    #[LiveListener('clientCase:created')]
    public function onCategoryCreated(): void
    {
        $this->isCreated = true;
        $this->message = "L'affaire a été créée avec succès";
    }
}