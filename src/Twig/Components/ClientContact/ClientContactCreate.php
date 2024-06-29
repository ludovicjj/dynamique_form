<?php

namespace App\Twig\Components\ClientContact;

use App\Entity\Client;
use App\Entity\ClientContact;
use App\Form\Type\ClientContactType;
use App\Form\Type\PartnerContactType;
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
class ClientContactCreate extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;
    use ComponentWithFormTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: true)]
    public ?Client $client = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ClientContactType::class);
    }

    public function hasValidationErrors(): bool
    {
        return $this->getForm()->isSubmitted() && !$this->getForm()->isValid();
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): void
    {
        $this->submitForm();

        /** @var ClientContact $clientContact */
        $clientContact = $this->getForm()->getData();
        $clientContact->setClient($this->client);

        $entityManager->persist($clientContact);
        $entityManager->flush();

        $this->emit('clientContact:alert', [
            'message' => "Le contact a été créée avec succès"
        ]);

        $this->dispatchBrowserEvent('modal:close');
        $this->resetForm();
        $this->resetValidation();
    }
}