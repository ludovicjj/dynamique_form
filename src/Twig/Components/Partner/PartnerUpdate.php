<?php

namespace App\Twig\Components\Partner;

use App\Form\Type\PartnerType;
use App\Repository\PartnerRepository;
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
class PartnerUpdate extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;
    use ComponentWithFormTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: true)]
    public ?string $id = null;

    public function __construct(private readonly PartnerRepository $partnerRepository)
    {
    }

    protected function instantiateForm(): FormInterface
    {
        $data = $this->partnerRepository->find($this->id);
        return $this->createForm(PartnerType::class, $data);
    }

    public function hasValidationErrors(): bool
    {
        return $this->getForm()->isSubmitted() && !$this->getForm()->isValid();
    }

    #[LiveAction]
    public function update(
        EntityManagerInterface $entityManager
    ): void {
        $this->submitForm();
        $entityManager->flush();

        $this->emit('partner:alert', [
            'message' => "Le partner a été modifié avec succès"
        ]);

        $this->dispatchBrowserEvent('modal:close');

        $this->resetForm();
        $this->resetValidation();
    }
}