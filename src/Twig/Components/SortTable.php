<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class SortTable
{
    public string $routeName;
    public string $sortName;

    public string $property;

    public string $direction;

    public function getIcon(): string
    {
        if ($this->property === $this->sortName) {
            return  ($this->direction === 'asc') ? 'chevron-up' : 'chevron-down';
        } else {
            return 'chevron-up-down';
        }
    }
}