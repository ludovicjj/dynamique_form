<?php

namespace App\Twig\Components\ClientCase;

use App\Entity\ClientCase;
use App\Entity\Document;
use App\Form\Type\ClientCaseDocumentType;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class ClientCaseDocumentForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;
    use ComponentWithFormTrait;
    use ValidatableComponentTrait;

    #[LiveProp]
    public ?ClientCase $initialFormData = null;

    #[LiveProp]
    public array $documentData = [];

    public function __construct(private readonly DocumentRepository $documentRepository)
    {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ClientCaseDocumentType::class, $this->initialFormData, [
            'action' => $this->generateUrl('app_client_case_document', ['id' => $this->initialFormData->getId()])
        ]);
    }

    public function mount(ClientCase $initialFormData): void
    {
        $this->initialFormData = $initialFormData;

        $documents = $this->documentRepository->findAllByClientCase($initialFormData);
        $this->documentData = array_map(function (Document $document) {
            return [
                'id' => $document->getId(),
                'name' => $document->getName(),
            ];
        }, $documents);
    }
}
