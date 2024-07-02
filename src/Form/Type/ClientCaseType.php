<?php

namespace App\Form\Type;

use App\Entity\BuildingCategory;
use App\Entity\Client;
use App\Entity\ClientCase;
use App\Entity\Country;
use App\Entity\Mission;
use App\Entity\Partner;
use App\Entity\PartnerContact;
use App\Entity\ProjectFeature;
use App\Entity\User;
use App\Form\EventListener\AddClientContactsFieldListener;
use App\Form\EventListener\AddParentFieldListener;
use App\Repository\ClientRepository;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class ClientCaseType extends AbstractType
{
    public function __construct(
        private readonly ClientRepository $clientRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ?ClientCase $clientCase */
        $clientCase = $options['data'] ?? null;
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
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'multiple' => false,
                'placeholder' => "Séléctionnez un client",
                'choice_label' => 'companyName',
                'label' => 'Client<span class="mandatory">*</span>',
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
                'placeholder' => "Séléctionnez un partenaire",
                'attr' => [
                    'data-client-case-target' => 'partner',
                    'data-action' => 'change->client-case#onChangePartner'
                ],
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
                },
                'attr' => [
                    'data-client-case-target' => 'partnerContact',
                    'class' => 'd-none choice-wrapper choice-wrapper-3'
                ],
            ])
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
            ->add('signedAt', DateType::class, [
                'label' => 'Convention/Commande du',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd-MM-yyyy',
                'input'  => 'datetime',
            ])
            ->add('directoryName', TextType::class, [
                'label' => 'Dossier Assurance D.O.'
            ])
            ->add('missions', EntityType::class, [
                'class' => Mission::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name'
            ])
            ->add('buildingCategory', EntityType::class, [
                'class' => BuildingCategory::class,
                'multiple' => false,
                'label' => 'Type de bâtiment<span class="mandatory">*</span>',
                'label_html' => true,
                'placeholder' => 'Sélectionnez un un type de bâtiment'
            ])
            ->add('description', TextareaType::class)
            ->add('manager', EntityType::class, [
                'class' => User::class,
                'multiple' => false,
                'label' => "Responsable d'affaire",
                'placeholder' => 'Sélectionnez un responsable'
            ])
            ->add('buildStartedAt', DateType::class, [
                'label' => "Début des travaux",
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd-MM-yyyy',
                'input'  => 'datetime'
            ])
            ->add('buildFinishedAt', DateType::class, [
                'label' => "Fin des travaux",
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd-MM-yyyy',
                'input'  => 'datetime'
            ])
            ->add('agreementAmount', TextType::class, [
                'label' => 'Montant travaux figurant dans la convention'
            ])
            ->add('lastKnowCost', TextType::class, [
                'label' => 'Dernier montant travaux connu'
            ])
            ->add('collaborators', EntityType::class, [
                'class' => User::class,
                'multiple' => true,
                'expanded' => true,
                'label' => "Collaborateur"
            ])
            ->add('isChild', ChoiceType::class, [
                'choices' => [
                    'Non' => false,
                    'Oui' => true
                ],
                'mapped' => false,
                'label' => 'Sous-affaire',
                'data' => $clientCase && $clientCase->getParentCase() !== null
            ])
            ->add('projectFeatures', EntityType::class, [
                'class' => ProjectFeature::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name',
                'label' => 'Caractéristiques'
            ])
        ;

        // Event
        $builder
            ->addEventSubscriber(new AddClientContactsFieldListener($this->clientRepository))
            ->addEventSubscriber(new AddParentFieldListener())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientCase::class,
            'csrf_protection' => false
        ]);
    }
}