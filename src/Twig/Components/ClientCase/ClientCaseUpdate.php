<?php

namespace App\Twig\Components\ClientCase;

use App\Entity\ClientCase;
use App\Form\Type\ClientCaseType;
use App\Repository\ClientCaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent]
class ClientCaseUpdate extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;
    use ComponentWithFormTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: true)]
    public ?string $id = null;

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ClientCaseRepository $clientCaseRepository
    )
    {
    }

    protected function instantiateForm(): FormInterface
    {
        $data = $this->clientCaseRepository->find($this->id);
        return $this->createForm(ClientCaseType::class, $data);
    }

    public function hasValidationErrors(): bool
    {
        return $this->getForm()->isSubmitted() && !$this->getForm()->isValid();
    }

    #[LiveAction]
    public function update(
        EntityManagerInterface $entityManager
    ): void {
        $this->submitForm();

        /** @var ClientCase $clientCase */
        $clientCase = $this->getForm()->getData();
        $entityManager->flush();

        $this->emit('clientCase:updated', [
            'clientCase' => $clientCase->getId()
        ]);

        $this->emit('reset');

        $this->resetForm();
        $this->resetValidation();
    }
}