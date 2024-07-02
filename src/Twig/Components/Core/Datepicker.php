<?php

namespace App\Twig\Components\Core;

use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Datepicker
{
    public ?FormView $field = null;
}