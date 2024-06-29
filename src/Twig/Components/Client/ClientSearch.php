<?php

namespace App\Twig\Components\Client;

use App\Repository\ClientRepository;
use App\Repository\CountryRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ClientSearch
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    public int $page = 1;

    #[LiveProp(writable: true)]
    public ?int $country = null;

    #[LiveProp(writable: true)]
    public ?int $clientId = null;

    public bool $isLoading = true;

    public bool $isSuccess = false;

    public string $message = '';

    private const PER_PAGE = 25;

    public function __construct(
        private readonly ClientRepository $clientRepository,
        private readonly CountryRepository $countryRepository
    ) {
    }

    public function getCountries(): array
    {
        return $this->countryRepository->findAll();
    }

    public function getClients(): array
    {
        $page = max($this->page, 1);

        return $this->clientRepository->searchPaginated(
            $page,
            self::PER_PAGE,
            $this->country
        );
    }

    #[LiveListener('client:create:modal')]
    public function onCreateModal(): void
    {
        $this->isLoading = false;
    }

    #[LiveListener('client:update:modal')]
    public function onUpdateModal(#[LiveArg] ?int $id = null): void
    {
        $this->isLoading = false;
        $this->clientId = $id;
    }

    #[LiveListener('client:alert')]
    public function onAlert(#[LiveArg] string $message = ''): void
    {
        $this->isSuccess = true;
        $this->message = $message;
    }

    #[LiveListener('reset')]
    public function reset(): void
    {
        $this->isLoading = true;
        $this->clientId = null;
        $this->dispatchBrowserEvent('modal:close');
    }
}