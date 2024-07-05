<?php

namespace App\Twig\Components\Document;

use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class DocumentForm
{
    public ?FormView $form = null;

    public ?string $id = null;

    public ?string $class = null;
}