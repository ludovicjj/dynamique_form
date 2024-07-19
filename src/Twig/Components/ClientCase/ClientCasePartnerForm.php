<?php

namespace App\Twig\Components\ClientCase;

use App\Entity\ClientCase;
use App\Form\Type\ClientCasePartnerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ClientCasePartnerForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ClientCase $initialFormData;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ClientCasePartnerType::class, $this->initialFormData, [
            'action' => $this->generateUrl('app_client_case_partner', ['id' => $this->initialFormData->getId()])
        ]);
    }

    public function mount(ClientCase $initialFormData): void
    {
        $this->initialFormData = $initialFormData;
    }
}