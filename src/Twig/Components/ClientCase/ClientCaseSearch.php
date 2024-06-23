<?php

namespace App\Twig\Components\ClientCase;

use App\Repository\ClientCaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
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

    #[LiveProp(writable: true, url: true)]
    public string $filter = '';

    #[LiveProp(writable: true)]
    public int $page = 1;

    private const PER_PAGE = 25;

    #[LiveProp]
    public ?int $clientCaseUpdateId = null;

    public bool $isSuccess = false;

    public string $message = '';

    public bool $isLoading = true;

    public function __construct(
        private readonly ClientCaseRepository $clientCaseRepository
    ) {
    }

    public function getClientCases(): array
    {
        $page = max($this->page, 1);

        return  $this->clientCaseRepository->searchPaginated($this->filter, $page, self::PER_PAGE);
    }

    public function hasMore(): bool
    {
        return $this->clientCaseRepository->clientCaseCount($this->filter) > ($this->page * self::PER_PAGE);
    }

    #[LiveAction]
    public function more(): void
    {
        ++$this->page;
    }

    #[LiveListener('clientCase:filter')]
    public function onFilter(): void
    {
        $this->page = 1;
    }

    #[LiveListener('clientCase:alert')]
    public function onClientCaseAlert(#[LiveArg] string $message = ''): void
    {
        $this->isSuccess = true;
        $this->message = $message;
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