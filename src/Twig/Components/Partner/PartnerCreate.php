<?php

namespace App\Twig\Components\Partner;

use App\Entity\Partner;
use App\Form\Type\PartnerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class PartnerCreate extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;
    use ComponentWithFormTrait;
    use ValidatableComponentTrait;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(PartnerType::class);
    }

    public function hasValidationErrors(): bool
    {
        return $this->getForm()->isSubmitted() && !$this->getForm()->isValid();
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): void
    {
        $this->submitForm();

        /** @var Partner $partner */
        $clientCase = $this->getForm()->getData();

        $entityManager->persist($clientCase);
        $entityManager->flush();

        $this->emit('partner:alert', [
            'message' => "Le partenaire a été créée avec succès"
        ]);

        $this->dispatchBrowserEvent('modal:close');
        $this->resetForm();
        $this->resetValidation();
    }
}