<?php

namespace App\Twig\Components\Core;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class BootstrapModal
{
    public ?string $id = null;

    public ?string $size = null;
}