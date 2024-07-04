<?php

namespace App\Twig\Components\ClientCase;

use App\Entity\ClientCase;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
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

    public bool $isSuccess = false;

    public string $message = '';

    public string $modalType = '';

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
    #[LiveListener('document:modal')]
    public function onShowModal(#[LiveArg] string $type = ''): void
    {
        $this->modalType = $type;
        $this->isLoading = false;
    }

    #[LiveListener('alert:show')]
    public function onShowAlert(#[LiveArg] string $message = ''): void
    {
        $this->isSuccess = true;
        $this->message = $message;
    }

    #[LiveListener('reset')]
    public function onReset(): void
    {
        $this->isLoading = true;
        $this->dispatchBrowserEvent('modal:close');
    }
}