<?php

namespace App\Form\Type;

use App\Entity\ClientCase;
use App\Entity\Partner;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientCaseDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('files', FileType::class, [
                'label' => false,
                'mapped' => false,
                'multiple' => true
            ])
            ->add('name', TextType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Désignation du document'
                ]
            ])
            ->add('addedAt', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => false,
                'mapped' => false
            ])
            ->add('reference', TextType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Ref. du document'
                ]
            ])
            ->add('createdBy', EntityType::class, [
                'class' => Partner::class,
                'choice_label' => 'companyName',
                'label' => false,
                'mapped' => false,
                'placeholder' => 'Emetteur',
            ])
            ->add('tag', TextType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => '#TAG'
                ]
            ])
            ->add('indice', ChoiceType::class, [
                'choices' => [
                    '0' => '0',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                ],
                'label' => false,
                'mapped' => false,
                'placeholder' => 'Indice'
            ])
            ->add('documents', CollectionType::class, [
                'entry_type' => DocumentType::class,
                'allow_add' => true,
                'label' => false,
                'allow_delete' => false,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => ClientCase::class]);
    }
}