<?php

namespace App\Twig\Components\Partner;

use App\Entity\Partner;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent]
class PartnerDelete extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    public ?int $id = null;

    #[LiveProp(writable: true)]
    public ?Partner $partner = null;

    #[LiveProp(writable: true)]
    public bool $loading = false;

    public function __construct(private readonly PartnerRepository $partnerRepository)
    {
    }

    #[PostMount]
    public function PostMount(): void
    {
        $this->partner = $this->partnerRepository->find($this->id);
    }

    #[LiveAction]
    public function delete(EntityManagerInterface $entityManager): void
    {
        $this->loading = true;
        $entityManager->remove($this->partner);
        $entityManager->flush();

        $this->emit('partner:alert', [
            'message' => "Le partenaire a été supprimé avec succès"
        ]);

        $this->dispatchBrowserEvent('modal:close');
    }
}