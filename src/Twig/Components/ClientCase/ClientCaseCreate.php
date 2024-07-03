<?php

namespace App\Twig\Components\ClientCase;

use App\Entity\ClientCase;
use App\Entity\User;
use App\Form\Type\ClientCaseCreateType;
use App\Repository\ClientCaseStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class ClientCaseCreate extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;
    use ComponentWithFormTrait;
    use ValidatableComponentTrait;

    protected function instantiateForm(): FormInterface
    {
        $clientCase = new ClientCase();
        return $this->createForm(ClientCaseCreateType::class, $clientCase);
    }

    public function hasValidationErrors(): bool
    {
        return $this->getForm()->isSubmitted() && !$this->getForm()->isValid();
    }

    #[LiveAction]
    public function save(
        EntityManagerInterface $entityManager,
        ClientCaseStatusRepository $clientCaseStatusRepository,
        #[CurrentUser] User $user
    ): void {
        $this->submitForm();

        /** @var ClientCase $clientCase */
        $clientCase = $this->getForm()->getData();
        $status = $clientCaseStatusRepository->find(1);

        $clientCase
            ->setClientCaseStatus($status)
            ->setCreatedBy($user);

        $entityManager->persist($clientCase);
        $entityManager->flush();

        $this->emit('clientCase:alert', [
            'message' => "L'affaire a été créée avec succès"
        ]);

        $this->dispatchBrowserEvent('modal:close');
        $this->resetForm();
        $this->resetValidation();
    }
}