<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class Counter
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $productCount = 0;

    #[LiveListener('category:updated')]
    public function onCategoryUpdated(#[LiveArg] int $id): void
    {

        // change category to the new one
        $this->productCount++;

        // the re-render will also cause the <select> to re-render with
        // the new option included
    }
}