<?php

namespace App\Twig\Components\ClientCase;

use App\Entity\ClientCase;
use App\Form\Type\ClientCaseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class ClientCaseCreate extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;
    use ComponentWithFormTrait;
    use ValidatableComponentTrait;

    #[LiveProp]
    public string $partnerAlert = "Vous devez selectionner un partenaire.";

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ClientCaseType::class);
    }

    public function hasValidationErrors(): bool
    {
        return $this->getForm()->isSubmitted() && !$this->getForm()->isValid();
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): void
    {
        $this->submitForm();

        /** @var ClientCase $clientCase */
        $clientCase = $this->getForm()->getData();

        $entityManager->persist($clientCase);
        $entityManager->flush();

        $this->emit('clientCase:alert', [
            'message' => "L'affaire a été créée avec succès"
        ]);

        $this->dispatchBrowserEvent('modal:close');
        $this->resetForm();
        $this->resetValidation();
    }
}