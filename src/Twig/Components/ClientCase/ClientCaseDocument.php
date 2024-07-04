<?php

namespace App\Twig\Components\ClientCase;

use App\Entity\ClientCase;
use App\Entity\Document;
use App\Entity\User;
use App\Form\DTO\DocumentDTO;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use DateTime;

#[AsLiveComponent]
class ClientCaseDocument extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: ['name', 'addedAt', 'addedBy', 'reference', 'indice', 'tag'])]
    public DocumentDTO $documentDto;

    #[LiveProp(writable: true)]
    public ?ClientCase $clientCase = null;

    public function __construct(
        private readonly PartnerRepository $partnerRepository
    ) {

    }

    public function mount(): void
    {
        $this->documentDto = new DocumentDTO();
    }

    #[ExposeInTemplate]
    public function getPartners(): array
    {
        return $this->partnerRepository->findAll();
    }

    #[LiveAction]
    public function save(
        EntityManagerInterface $entityManager,
        #[CurrentUser] User $user
    ): void {
        $document = $this->buildDocument($this->documentDto, $user);
        $entityManager->persist($document);
        $entityManager->flush();

        $this->emitUp('alert:show', [
            'message' => "Les documents ont été modifiés avec succès"
        ]);

        $this->dispatchBrowserEvent('modal:close');
    }

    private function buildDocument(DocumentDTO $documentDTO, User $user): Document
    {
        $document = new Document();
        $document
            ->setName($documentDTO->name)
            ->setAddedBy($user)
            ->setTag($documentDTO->tag)
            ->setIndice($documentDTO->indice)
            ->setReference($documentDTO->reference)
            ->setClientCase($this->clientCase);

        if ($documentDTO->addedBy) {
            $partner = $this->partnerRepository->find($documentDTO->addedBy);
            $document->setCreatedBy($partner);
        }

        if ($documentDTO->addedAt) {
            $addedAt = DateTime::createFromFormat('Y-m-d', $documentDTO->addedAt);
            $document->setAddedAt($addedAt);
        }

        return $document;
    }
}