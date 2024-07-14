<?php

namespace App\Twig\Components\PartnerContact;

use App\Entity\Partner;
use App\Entity\PartnerContact;
use App\Form\Type\PartnerContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class PartnerContactForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public PartnerContact $initialFormData;

    #[LiveProp]
    public ?Partner $partner = null;

    protected function instantiateForm(): FormInterface
    {
        $partnerContact = $this->initialFormData ?? new PartnerContact();

        return $this->createForm(PartnerContactType::class, $partnerContact, [
            'action' => $partnerContact->getId()
                ? $this->generateUrl('app_partner_contact_update', ['id' => $partnerContact->getId()])
                : $this->generateUrl('app_partner_contact_create', ['id' => $this->partner->getId()]),
        ]);
    }
}