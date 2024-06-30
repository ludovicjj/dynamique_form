<?php

namespace App\Twig\Components\ClientContact;

use App\Entity\ClientContact;
use App\Repository\ClientContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent]
class ClientContactDelete
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    public ?int $id = null;

    #[LiveProp(writable: true)]
    public ?ClientContact $clientContact = null;

    #[LiveProp(writable: true)]
    public bool $loading = false;

    public function __construct(
        private readonly ClientContactRepository $clientContactRepository
    ) {
    }

    #[PostMount]
    public function PostMount(): void
    {
        $this->clientContact = $this->clientContactRepository->find($this->id);
    }

    #[LiveAction]
    public function delete(EntityManagerInterface $entityManager): void
    {
        $this->loading = true;
        $entityManager->remove($this->clientContact);
        $entityManager->flush();

        $this->emit('clientContact:alert', [
            'message' => "Le contact a été supprimé avec succès"
        ]);

        $this->emit('reset');
    }
}