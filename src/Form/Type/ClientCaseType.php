<?php

namespace App\Form\Type;

use App\Entity\ClientCase;
use App\Entity\Country;
use App\Entity\Partner;
use App\Entity\PartnerContact;
use App\Repository\CountryRepository;
use App\Repository\PartnerContactRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class ClientCaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);

        $builder
            ->add('projectName', TextType::class, [
                'label' => 'Nom de l\'affaire<span class="mandatory">*</span>',
                'label_html' => true
            ])
            ->add('reference', TextType::class, [
                'label' => 'Référence<span class="mandatory">*</span>',
                'label_html' => true
            ])
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
            ])

            ->addDependent('partnerContacts', 'partner', function (DependentField $field, ?Partner $partner) {
                if ($partner) {
                    $field->add(EntityType::class, [
                        'class' => PartnerContact::class,
                        'mapped' => true,
                        'multiple' => true,
                        'expanded' => true,
                        'choice_label' => function($partnerContact) {
                            return $partnerContact->getLastname() . ' ' . $partnerContact->getFirstname();
                        },
                        'query_builder' => function(PartnerContactRepository $er) use ($partner): QueryBuilder {
                            return $er->findByPartnerQueryBuilder($partner);
                        },
                        'label' => 'Partenaire Contact'
                    ]);
                }
            })

            ->add('address1', TextType::class, [
                'label' => 'Adresse<span class="mandatory">*</span>',
                'label_html' => true
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville<span class="mandatory">*</span>',
                'label_html' => true
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'Code Postal<span class="mandatory">*</span>',
                'label_html' => true
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'multiple' => false,
                'choice_label' => 'name',
                'query_builder' => function(CountryRepository $er): QueryBuilder {
                    return $er->findAllOrderByNameQueryBuilder();
                },
                'label' => 'Pays<span class="mandatory">*</span>',
                'label_html' => true,
                'placeholder' => 'Choisissez un pays'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientCase::class,
            'csrf_protection' => false,
        ]);
    }
}