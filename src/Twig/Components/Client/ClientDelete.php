<?php

namespace App\Twig\Components\Client;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent]
class ClientDelete
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    public ?int $id = null;

    #[LiveProp(writable: true)]
    public ?Client $client = null;

    #[LiveProp(writable: true)]
    public bool $loading = false;

    public function __construct(
        private readonly ClientRepository $clientRepository
    ) {
    }

    #[PostMount]
    public function PostMount(): void
    {
        $this->client = $this->clientRepository->find($this->id);
    }

    #[LiveAction]
    public function delete(EntityManagerInterface $entityManager): void
    {
        $this->loading = true;
        $entityManager->remove($this->client);
        $entityManager->flush();

        $this->emit('client:alert', [
            'message' => "Le client a été supprimé avec succès"
        ]);

        $this->emit('reset');
    }
}