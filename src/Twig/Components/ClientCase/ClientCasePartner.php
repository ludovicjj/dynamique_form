<?php

namespace App\Twig\Components\ClientCase;

use App\Entity\ClientCase;
use App\Form\Type\ClientCaseUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class ClientCasePartner extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;
    use ComponentWithFormTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: true)]
    public ?ClientCase $clientCase = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ClientCaseUpdateType::class, $this->clientCase);
    }

    public function hasValidationErrors(): bool
    {
        return $this->getForm()->isSubmitted() && !$this->getForm()->isValid();
    }

}