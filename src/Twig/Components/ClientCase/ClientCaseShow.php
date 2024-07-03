<?php

namespace App\Twig\Components\ClientCase;

use App\Entity\ClientCase;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ClientCaseShow
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public ?ClientCase $clientCase = null;

    public bool $isLoading = true;

    public function getPartners(): array
    {
        $partners = [];
        foreach ($this->clientCase->getPartnerContacts() as $partnerContact) {
            $partner = $partnerContact->getPartner();
            $partnerId = $partner->getId();

            if (!isset($partners[$partnerId])) {
                $partners[$partnerId] = [
                    'companyName' => $partner->getCompanyName(),
                    'partnerContacts' => []
                ];
            }

            $partners[$partnerId]['partnerContacts'][] = $partnerContact;
        }

        return $partners;
    }

    #[LiveListener('partner:modal')]
    public function onCreateModal(): void
    {
        $this->isLoading = false;
    }

    #[LiveListener('reset')]
    public function onReset(): void
    {
        $this->isLoading = true;
        $this->dispatchBrowserEvent('modal:close');
    }
}