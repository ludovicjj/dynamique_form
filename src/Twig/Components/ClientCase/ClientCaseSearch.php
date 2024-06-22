<?php

namespace App\Twig\Components\ClientCase;

use App\Repository\ClientCaseRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
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

    #[LiveProp]
    public ?int $clientCaseUpdateId = null;

    public bool $isSuccess = false;

    public string $message = '';

    public bool $isLoading = true;

    public function __construct(
        private readonly ClientCaseRepository $clientCaseRepository,
        private readonly RequestStack $requestStack,
        private readonly PaginatorInterface $paginator
    )
    {
    }


    public function getClientCases(): array
    {
        $request = $this->requestStack->getMainRequest();
        $page = max($request->query->get('page', 1), 1);

        return $this->clientCaseRepository->searchPaginated($this->filter);
    }

    #[LiveListener('clientCase:created')]
    public function onCategoryCreated(): void
    {
        $this->isSuccess = true;
        $this->message = "L'affaire a été créée avec succès";
    }

    #[LiveListener('clientCase:updated')]
    public function onCategoryUpdated(): void
    {
        $this->isSuccess = true;
        $this->message = "L'affaire a été modifié avec succès";
    }

    #[LiveListener('clientCase:update:modal')]
    public function onUpdateModal(#[LiveArg] int $id): void
    {
        $this->isLoading = false;
        $this->clientCaseUpdateId = $id;
    }

    #[LiveListener('reset')]
    public function onReset(): void
    {
        $this->clientCaseUpdateId = null;
        $this->isLoading = true;
        $this->dispatchBrowserEvent('modal:close');
    }
}