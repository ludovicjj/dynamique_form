<?php

namespace App\Form\Type;

use App\Entity\Document;
use App\Entity\Partner;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Désignation du document'
                ]
            ])
            ->add('addedAt', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => false
            ])
            ->add('reference', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ref. du document'
                ],
            ])
            ->add('createdBy', EntityType::class, [
                'class' => Partner::class,
                'choice_label' => 'companyName',
                'label' => false,
                'placeholder' => 'Emetteur'
            ])
            ->add('tag', TextType::class, [
                'label' => false,
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
                'placeholder' => 'Indice'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class
        ]);
    }
}