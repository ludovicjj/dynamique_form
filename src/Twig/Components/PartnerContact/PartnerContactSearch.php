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

    private const PER_PAGE = 25;

    public bool $isSuccess = false;

    public string $message = '';

    public function __construct(
        private readonly PartnerContactRepository $partnerContactRepository
    ) {
    }

    #[LiveListener('partnerContact:alert')]
    public function onCategoryAlert(#[LiveArg] string $message = ''): void
    {
        $this->isSuccess = true;
        $this->message = $message;
    }

    public function getPartnerContacts(): array
    {
        return $this->partnerContactRepository->searchPaginated($this->partner);
    }
}