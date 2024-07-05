<?php

namespace App\Twig\Components\Core;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Alert
{
    public string $type = 'success';
    public ?string $message = null;
    public array $messages = [];

    public function getIcon(): string
    {
        return match ($this->type) {
            'success' => 'circle-check',
            'danger' => 'alert-circle',
            default => 'circle-check'
        };
    }
}