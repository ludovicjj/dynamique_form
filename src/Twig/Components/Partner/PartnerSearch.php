<?php

namespace App\Twig\Components\Partner;

use App\Entity\Country;
use App\Repository\CountryRepository;
use App\Repository\PartnerRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class PartnerSearch
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    public int $page = 1;

    #[LiveProp(writable: true)]
    public ?string $filter = '';

    #[LiveProp(writable: true)]
    public ?int $partnerId = null;

    #[LiveProp(writable: true)]
    public ?int $country = null;

    public bool $isLoading = true;

    public bool $isSuccess = false;

    public string $message = '';

    private const PER_PAGE = 25;

    public function __construct(
        private readonly PartnerRepository $partnerRepository,
        private readonly CountryRepository $countryRepository
    ) {
    }

    public function getPartners(): array
    {
        $page = max($this->page, 1);

        return $this->partnerRepository->searchPaginated(
            $page,
            self::PER_PAGE,
            $this->country
        );
    }

    public function getCountries(): array
    {
        return $this->countryRepository->findAll();
    }

    #[LiveListener('partner:alert')]
    public function onAlert(#[LiveArg] string $message = ''): void
    {
        $this->isSuccess = true;
        $this->message = $message;
    }

    #[LiveListener('partner:update:modal')]
    public function onUpdateModal(#[LiveArg] int $id): void
    {
        $this->isLoading = false;
        $this->partnerId = $id;
    }

    #[LiveListener('partner:delete:modal')]
    public function onDeleteModal(#[LiveArg] int $id): void
    {
        $this->isLoading = false;
        $this->partnerId = $id;
    }

    #[LiveListener('partner:create:modal')]
    public function onCreateModal(): void
    {
        $this->isLoading = false;
        $this->partnerId = null;
    }

    #[LiveListener('reset')]
    public function onReset(): void
    {
        $this->partnerId = null;
        $this->isLoading = true;
        $this->dispatchBrowserEvent('modal:close');
    }
}