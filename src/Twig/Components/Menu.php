<?php

namespace App\Twig\Components;

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
            'label' => 'Register',
            'route' => 'app_registration',
            'icon' => 'file'
        ],
        [
            'label' => 'Affaires',
            'route' => 'app_client_case',
            'icon' => 'file'
        ],
        [
            'label' => 'Products',
            'route' => 'app_product',
            'icon' => 'users-plus'
        ],
        [
            'label' => 'Categories',
            'route' => 'app_category',
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
