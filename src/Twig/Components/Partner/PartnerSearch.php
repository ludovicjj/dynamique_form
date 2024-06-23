<?php

namespace App\Twig\Components\Partner;

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

    #[LiveProp]
    public ?int $partnerUpdateId = null;

    public bool $isSuccess = false;

    public string $message = '';

    public bool $isLoading = true;

    private const PER_PAGE = 25;

    public function __construct(
        private readonly PartnerRepository $partnerRepository
    ) {
    }

    public function getPartners(): array
    {
        $page = max($this->page, 1);

        return  $this->partnerRepository->searchPaginated($page, self::PER_PAGE);
    }

    #[LiveListener('partner:event')]
    public function onCategoryCreated(#[LiveArg] string $message = ''): void
    {
        $this->isSuccess = true;
        $this->message = $message;
    }

    #[LiveListener('partner:update:modal')]
    public function onUpdateModal(#[LiveArg] int $id): void
    {
        $this->isLoading = false;
        $this->partnerUpdateId = $id;
    }

    #[LiveListener('reset')]
    public function onReset(): void
    {
        $this->partnerUpdateId = null;
        $this->isLoading = true;
        $this->dispatchBrowserEvent('modal:close');
    }
}