<?php

namespace App\Twig\Components\PartnerContact;

use App\Entity\Partner;
use App\Repository\PartnerContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class PartnerContactSearch extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    public ?Partner $partner = null;

    #[LiveProp]
    public ?int $partnerContactUpdateId = null;

    private const PER_PAGE = 25;

    public bool $isLoading = true;

    public bool $isSuccess = false;

    public string $message = '';

    public function __construct(
        private readonly PartnerContactRepository $partnerContactRepository
    ) {
    }

    public function getPartnerContacts(): array
    {
        return $this->partnerContactRepository->searchPaginated($this->partner);
    }

    #[LiveListener('partnerContact:alert')]
    public function onCategoryAlert(#[LiveArg] string $message = ''): void
    {
        $this->isSuccess = true;
        $this->message = $message;
    }

    #[LiveListener('partnerContact:update:modal')]
    public function onUpdateModal(#[LiveArg] int $id): void
    {
        $this->isLoading = false;
        $this->partnerContactUpdateId = $id;
    }

    #[LiveListener('reset')]
    public function onReset(): void
    {
        $this->partnerContactUpdateId = null;
        $this->isLoading = true;
        $this->dispatchBrowserEvent('modal:close');
    }
}