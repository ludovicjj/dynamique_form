<?php

namespace App\Twig\Components\Core;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Breadcrumbs
{
    public array $items = [];
}