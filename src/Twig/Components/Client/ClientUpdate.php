<?php

namespace App\Twig\Components\Client;

use App\Entity\Client;
use App\Form\Type\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\Attribute\PreReRender;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent]
class ClientUpdate extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;
    use ValidatableComponentTrait;

//    #[LiveProp(writable: true)]
//    public ?string $id = null;

    #[LiveProp]
    public ?Client $initialFormData = null;

    public function __construct(
        private readonly ClientRepository $clientRepository
    ) {
    }

    public function mount(int $id): void
    {
        $this->initialFormData = $this->clientRepository->find($id);
        $this->resetForm();
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ClientType::class, $this->initialFormData);
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