<?php

namespace App\Twig\Components;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class CreateCategoryForm
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    #[NotBlank]
    public string $name = '';

    public bool $isLoad = true;

    public function __construct(private readonly CategoryRepository $categoryRepository)
    {
    }

    #[LiveListener('category:updated')]
    public function onCategoryUpdated(#[LiveArg] int $id): void
    {
        $category = $this->categoryRepository->find($id);

        if ($category) {
            $this->name = $category->getName();
        } else {
            $this->name = 'yolo';
        }

        $this->isLoad = false;
        // change category to the new one


        // the re-render will also cause the <select> to re-render with
        // the new option included
    }

    #[LiveAction]
    public function saveCategory(EntityManagerInterface $entityManager): void
    {
        $this->validate();

        $category = new Category();
        $category->setName($this->name);

        $entityManager->persist($category);
        $entityManager->flush();

        // from ComponentToolsTrait
        $this->dispatchBrowserEvent('modal:close');

        $this->emit('category:created', [
            'category' => $category->getId(),
        ]);

        // reset the fields in case the modal is opened again
        $this->name = '';

        // reset validation message
        $this->resetValidation();
    }
}