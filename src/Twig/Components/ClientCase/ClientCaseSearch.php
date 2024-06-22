<?php

namespace App\Twig\Components\ClientCase;

use App\Repository\ClientCaseRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ClientCaseSearch
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    public string $filter = '';

    #[LiveProp(writable: true)]
    public string $query = '';

    public bool $isCreated = false;

    public string $message = '';

    public function __construct(
        private readonly ClientCaseRepository $clientCaseRepository,
        private readonly RequestStack $requestStack
    )
    {
    }

    public function getClientCases(): array
    {
        $request = $this->requestStack->getMainRequest();

        $page = max($request->query->get('page', 1), 1);

        return $this->clientCaseRepository->search($this->filter);
    }

    #[LiveListener('clientCase:created')]
    public function onCategoryCreated(): void
    {
        $this->isCreated = true;
        $this->message = "L'affaire a été créée avec succès";
    }
}