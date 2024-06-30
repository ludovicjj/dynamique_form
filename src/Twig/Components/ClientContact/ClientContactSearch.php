<?php

namespace App\Twig\Components\ClientContact;

use App\Entity\Client;
use App\Repository\ClientContactRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ClientContactSearch
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    public int $page = 1;

    #[LiveProp(writable: true)]
    public ?Client $client = null;

    #[LiveProp]
    public ?int $clientContactId = null;

    public bool $isLoading = true;

    public bool $isSuccess = false;

    public string $message = '';

    private const PER_PAGE = 25;

    public function __construct(
        private readonly ClientContactRepository $clientContactRepository
    ) {
    }

    public function getClientContacts(): array
    {
        $page = max($this->page, 1);
        return $this->clientContactRepository->searchPaginated(
            $this->client,
            $page,
            self::PER_PAGE
        );
    }

    #[LiveListener('clientContact:alert')]
    public function onCategoryAlert(#[LiveArg] string $message = ''): void
    {
        $this->isSuccess = true;
        $this->message = $message;
    }

    #[LiveListener('clientContact:create:modal')]
    public function onCreateModal(): void
    {
        $this->isLoading = false;
    }

    #[LiveListener('clientContact:update:modal')]
    public function onUpdateModal(#[LiveArg] ?int $id = null): void
    {
        $this->isLoading = false;
        $this->clientContactId = $id;
    }

    #[LiveListener('clientContact:delete:modal')]
    public function onDeleteModal(#[LiveArg] ?int $id = null): void
    {
        $this->isLoading = false;
        $this->clientContactId = $id;
    }

    #[LiveListener('reset')]
    public function onReset(): void
    {
        $this->clientContactId = null;
        $this->isLoading = true;
        $this->dispatchBrowserEvent('modal:close');
    }
}