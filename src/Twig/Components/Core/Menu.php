<?php

namespace App\Twig\Components\Core;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Menu
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public bool $open = false;

    public array $links = [
        [
            'label' => 'Affaires',
            'route' => 'app_client_case',
            'icon' => 'file'
        ],
        [
            'label' => 'Partenaires',
            'route' => 'app_partner',
            'icon' => 'users-plus'
        ],
        [
            'label' => 'Clients',
            'route' => 'app_client',
            'icon' => 'building-community'
        ],
        [
            'label' => 'Deconnection',
            'route' => 'app_logout',
            'icon' => 'logout'
        ],
    ];

    public function mount(): void
    {
        $request = $this->requestStack->getMainRequest();

        if(!$request) {
            return;
        }

        $session = $request->getSession();

        $this->open = $session->get('menu_state', false);
    }
}
