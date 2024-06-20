<?php

namespace App\Twig\Components\ClientCase;

use App\Repository\ClientCaseRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
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

    public function __construct(private readonly ClientCaseRepository $clientCaseRepository)
    {
    }

    public function getClientCases(): array
    {
        // example method that returns an array of Products
        return $this->clientCaseRepository->search($this->filter);
    }
}