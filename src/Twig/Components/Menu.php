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
            'label' => 'Home',
            'route' => 'app_home'
        ],
        [
            'label' => 'Registration',
            'route' => 'app_registration'
        ],
        [
            'label' => 'Products',
            'route' => 'app_product'
        ],
        [
            'label' => 'Categories',
            'route' => 'app_category'
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
