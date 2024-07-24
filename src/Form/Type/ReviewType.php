<?php

namespace App\Form\Type;

use App\Entity\Document;
use App\Entity\Report;
use App\Entity\Review;
use App\Entity\ReviewGroup;
use App\Entity\ReviewValue;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Report $report */
        $report = $options['report'];

        $builder
            ->add('reviewValue', EntityType::class, [
                'class' => ReviewValue::class,
                'choice_label' => 'name',
                'label' => "Avis<span class='mandatory'>*</span>",
                'label_html' => true,
                'multiple' => false,
                'expanded' => true
            ])

            ->add('reviewGroup', EntityType::class, [
                'class' => ReviewGroup::class,
                'choice_label' => 'name',
                'placeholder' => 'objet',
                'label' => "Objet<span class='mandatory'>*</span>",
                'label_html' => true,
                'multiple' => false,
                'expanded' => false

            ])
            ->add('observation', TextareaType::class);

        if ($report->getReportType()->getCode() !== 'FCE') {
            $builder
                ->add('searchDocument', TextType::class, [
                    'label' => false,
                    'mapped' => false,
                    'attr' => [
                        'placeholder' => 'Rechercher un document'
                    ]
                ])
                ->add('documents', EntityType::class, [
                    'class' => Document::class,
                    'label' => "Documents",
                    'choice_label' => 'id',
                    'multiple' => true,
                    'expanded' => true,
                    'query_builder' => function (EntityRepository $er) use ($report): QueryBuilder {
                        $queryBuilder = $er->createQueryBuilder('document');
                        return $queryBuilder
                            ->leftJoin('document.clientCase', 'client_case')
                            ->andWhere('client_case = :client_case')
                            ->setParameter('client_case', $report->getClientCase());
                    },
                    'choice_attr' => function (Document $document) {
                        return [
                            'data-name' => $document->getName(),
                            'data-created-at' => $document->getCreatedAt()?->format('d-m-Y'),
                            'data-tag' => $document->getTag(),
                            'data-indice' => $document->getIndice(),
                            'data-reference' => $document->getReference()
                        ];
                    },
                ]);
        }

        if ($report->getReportType()->getCode() !== 'AD') {
            $builder
                ->add('visitedAt', DateType::class, [
                    'label' => "Visité le",
                    'widget' => 'single_text',
                    'html5' => false,
                    'format' => 'dd-MM-yyyy',
                    'input'  => 'datetime',
                    'attr' => [
                        'autocomplete'=> 'off'
                    ]
                ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
            'report' => null,
        ]);

    }
}