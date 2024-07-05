<?php

namespace App\Twig\Components\Document;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class DocumentCollectionForm
{
    public mixed $form = null;

    public ?string $id = null;

    public ?string $class = null;
}