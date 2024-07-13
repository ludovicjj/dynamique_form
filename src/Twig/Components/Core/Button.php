<?php

namespace App\Twig\Components\Core;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class Button
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveAction]
    public function onClick(#[LiveArg] int $id): void
    {
        $this->emit('category:updated',
            ['id' => $id]
        );
    }
}