<?php

namespace App\Twig\Components\ClientCase;

use App\Entity\ClientCase;
use App\Repository\ClientCaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent]
class ClientCaseDelete
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    public ?int $id = null;

    #[LiveProp(writable: true)]
    public bool $loading = false;

    #[LiveProp(writable: true)]
    public ?ClientCase $clientCase = null;

    public function __construct(
        private readonly ClientCaseRepository $clientCaseRepository
    ) {
    }

    #[PostMount]
    public function PostMount(): void
    {
        $this->clientCase = $this->clientCaseRepository->find($this->id);
    }

    #[LiveAction]
    public function delete(EntityManagerInterface $entityManager): void
    {
        $this->loading = true;
        $entityManager->remove($this->clientCase);
        $entityManager->flush();

        $this->emit('clientCase:alert', [
            'message' => "L'affaire' a été supprimé avec succès"
        ]);

        $this->dispatchBrowserEvent('modal:close');
    }
}