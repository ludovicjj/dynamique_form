<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class MenuController extends AbstractController
{
    #[Route('/toggle-menu', name: 'app_toggle_menu', methods: ['POST'])]
    public function toggleMenu(Request $request, SessionInterface $session): JsonResponse
    {
        $data = $request->toArray();
        $menuState = $data['open'] ?? false;

        $session->set('menu_state', $menuState);

        return new JsonResponse(['success' => true]);
    }
}