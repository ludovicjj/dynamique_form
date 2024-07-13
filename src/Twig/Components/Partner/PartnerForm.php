<?php

namespace App\Twig\Components\Partner;

use App\Entity\Partner;
use App\Form\Type\PartnerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class PartnerForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public Partner $initialFormData;

    protected function instantiateForm(): FormInterface
    {
        $partner = $this->initialFormData ?? new Partner();

        return $this->createForm(PartnerType::class, $partner, [
            'action' => $partner->getId()
                ? $this->generateUrl('app_partner_update', ['id' => $partner->getId()])
                : $this->generateUrl('app_partner_create'),
        ]);
    }
}