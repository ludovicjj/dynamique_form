<?php

namespace App\Twig\Components\PartnerContact;

use App\Entity\PartnerContact;
use App\Repository\PartnerContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent]
class PartnerContactDelete extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    public function __construct(
        private readonly PartnerContactRepository $partnerContactRepository
    ) {
    }

    #[LiveProp(writable: true)]
    public ?int $id = null;

    #[LiveProp(writable: true)]
    public ?PartnerContact $partnerContact = null;

    #[PostMount]
    public function PostMount(): void
    {
        $this->partnerContact = $this->partnerContactRepository->find($this->id);
    }

    #[LiveAction]
    public function delete(EntityManagerInterface $entityManager): void
    {
        $entityManager->remove($this->partnerContact);
        $entityManager->flush();

        $this->emit('partnerContact:alert', [
            'message' => "Le contact a été supprimé avec succès"
        ]);

        $this->emit('reset');
    }
}