<?php

namespace App\Twig\Components\ClientContact;

use App\Entity\Client;
use App\Entity\ClientContact;
use App\Form\Type\ClientContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ClientContactForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?ClientContact $initialFormData;

    #[LiveProp]
    public ?Client $client = null;

    protected function instantiateForm(): FormInterface
    {
        $clientContact = $this->initialFormData ?? new ClientContact();

        return $this->createForm(ClientContactType::class, $clientContact, [
            'action' => $clientContact->getId()
                ? $this->generateUrl('app_client_contact_update', ['id' => $clientContact->getId()])
                : $this->generateUrl('app_client_contact_create', ['id' => $this->client->getId()]),
        ]);
    }
}