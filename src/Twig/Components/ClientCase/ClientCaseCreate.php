<?php

namespace App\Twig\Components\ClientCase;

use App\Form\Type\ClientCaseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ClientCaseCreate extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;
    use ComponentWithFormTrait;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ClientCaseType::class);
    }
}