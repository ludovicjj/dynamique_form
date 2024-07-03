<?php

namespace App\Form\Type;

use App\Entity\ClientCaseStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ClientCaseUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clientCaseStatus', EntityType::class, [
                'class' => ClientCaseStatus::class,
                'multiple' => false,
                'choice_label' => 'name',
                'label' => 'Status'
            ])
            ->add('isDraft', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'label' => 'Brouillon',
            ])
        ;
    }

    public function getParent(): string
    {
        return ClientCaseCreateType::class;
    }
}