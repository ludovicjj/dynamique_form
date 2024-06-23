<?php

namespace App\Twig\Components;

use App\Form\Type\CKeditorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class CkEditor extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CKeditorType::class);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): void
    {
        $this->submitForm();


        $data = $this->getForm()->getData();
        dd($data);

    }
}