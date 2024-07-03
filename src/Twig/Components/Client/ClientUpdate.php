<?php

namespace App\Twig\Components\Client;

use App\Form\Type\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class ClientUpdate extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: true)]
    public ?string $id = null;

    public function __construct(
        private readonly ClientRepository $clientRepository
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        $data = $this->clientRepository->find($this->id);
        return $this->createForm(ClientType::class, $data);
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

        $this->emit('client:alert', [
            'message' => "Le client a été modifié avec succès"
        ]);

        $this->dispatchBrowserEvent('modal:close');

        $this->resetForm();
        $this->resetValidation();
    }
}