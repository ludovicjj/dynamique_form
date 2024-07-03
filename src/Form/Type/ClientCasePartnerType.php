<?php

namespace App\Form\Type;

use App\Entity\ClientCase;
use App\Entity\Partner;
use App\Entity\PartnerContact;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientCasePartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('partner', EntityType::class, [
                'class' => Partner::class,
                'mapped' => false,
                'required' => false,
                'query_builder' => function(EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.companyName', 'ASC');
                },
                'label' => 'Partenaires',
                'choice_label' => 'companyName',
                'placeholder' => "Séléctionnez un partenaire"
            ])
            ->add('partnerContacts', EntityType::class, [
                'class' => PartnerContact::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => function($partnerContact) {
                    return $partnerContact->getFullName();
                },
                'choice_attr' => function($partnerContact) {
                    return [
                        'data-id' => $partnerContact->getPartner()->getId()
                    ];
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => ClientCase::class
            ]);
    }
}