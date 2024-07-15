<?php

namespace App\Twig\Components\ClientCase;

use App\Entity\ClientCase;
use App\Form\Type\ClientCaseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ClientCaseForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ClientCase $initialFormData;

    protected function instantiateForm(): FormInterface
    {
        $clientCase = $this->initialFormData ?? new ClientCase();

        return $this->createForm(ClientCaseType::class, $clientCase, [
            'action' => $clientCase->getId()
                ? $this->generateUrl('app_client_case_update', ['id' => $clientCase->getId()])
                : $this->generateUrl('app_client_case_create')
        ]);
    }
}