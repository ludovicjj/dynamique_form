<?php

namespace App\Twig\Components\ClientCase;

use App\Entity\ClientCase;
use App\Entity\Document;
use App\Entity\User;
use App\Form\Type\ClientCaseDocumentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
class ClientCaseDocument extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;
    use ComponentWithFormTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: true)]
    public ?ClientCase $clientCase = null;

    #[LiveProp]
    public array $documentData = [];

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ClientCaseDocumentType::class, $this->clientCase);
    }

    public function mount($clientCase): void
    {
        $this->clientCase = $clientCase;

        foreach ($clientCase->getDocuments() as $document) {
            $this->documentData[] = [
                'id' => $document->getId(),
                'name' => $document->getName(),
            ];
        }
    }

    #[LiveAction]
    public function save(
        EntityManagerInterface $entityManager,
        #[CurrentUser] User $user
    ): void {
        $this->submitForm();

        try {
            throw new UnprocessableEntityHttpException();
        } catch (UnprocessableEntityHttpException) {
            $this->getForm()->addError(new FormError('General error'));
            $this->getForm()->get('name')->addError(new FormError('Field error'));
            $this->formView = null;
        }

//        if ($this->getForm()->isSubmitted() && $this->isValid()) {
//            dd('valid');
//            $this->submitForm();
//            $this->getForm()->getData();
//
//
//            $this->submitForm();
//            $document = $this->buildDocument($user);
//
//            $entityManager->persist($document);
//            $entityManager->flush();
//
//            $this->emitUp('alert:show', [
//                'message' => "Les documents ont été modifiés avec succès"
//            ]);
//
//            $this->dispatchBrowserEvent('modal:close');
//        }
    }

    private function buildDocument(User $user): Document
    {
        $form = $this->getForm();
        $document = new Document();
        $document
            ->setName($form->get('name')->getData())
            ->setAddedBy($user)
            ->setTag($form->get('tag')->getData())
            ->setIndice($form->get('indice')->getData())
            ->setReference($form->get('reference')->getData())
            ->setClientCase($this->clientCase)
            ->setCreatedBy($form->get('createdBy')->getData())
            ->setAddedAt($form->get('addedAt')->getData());

        return $document;
    }
}