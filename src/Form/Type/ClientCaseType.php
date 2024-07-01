<?php

namespace App\Form\Type;

use App\Entity\Client;
use App\Entity\ClientCase;
use App\Entity\ClientContact;
use App\Entity\Country;
use App\Entity\Partner;
use App\Entity\PartnerContact;
use App\Repository\ClientContactRepository;
use App\Repository\ClientRepository;
use App\Repository\CountryRepository;
use App\Repository\PartnerContactRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class ClientCaseType extends AbstractType
{
    public function __construct(
        private readonly ClientRepository $clientRepository
    ) {
    }

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
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'multiple' => false,
                'placeholder' => "Séléctionnez un client",
                'choice_label' => 'companyName',
                'label' => 'Client<span class="mandatory">*</span>',
                'label_html' => true
            ])
//            ->addDependent('clientContacts', 'client', function (DependentField $field, ?Client $client) {
//                if ($client) {
//                    $field->add(EntityType::class, [
//                        'class' => ClientContact::class,
//                        'multiple' => true,
//                        'expanded' => true,
//                        'choice_label' => function($clientContact) {
//                            return $clientContact->getFullName();
//                        },
//
//                        'query_builder' => function(ClientContactRepository $er) use ($client): QueryBuilder {
//                            return $er->findByClientQueryBuilder($client);
//                        }
//                    ]);
//                }
//            })
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
                    'class' => 'd-none pc-wrapper'
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
                'label' => 'Date de signature',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd-MM-yyyy',
                'input'  => 'datetime',
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete'=> 'off',
                    'data-datepicker-target' => 'input',
                ],
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
           $this->addClientContact($event->getData(), $event->getForm(), $event);
        });
    }

    private function addClientContact(
        ?array $clientCase,
        FormInterface $form,
        FormEvent $event
    ): void {
        if (!$clientCase) {
            return;
        }

        if (isset($clientCase['client']) && $clientCase['client']) {
            $client = $this->clientRepository->find($clientCase['client']);

            if (isset($clientCase['clientContacts'])) {
                $allowedIds = $client->getClientContacts()->map(function ($contact) {
                    return $contact->getId();
                })->toArray();

                $filteredContacts = array_filter($clientCase['clientContacts'], function($contact) use ($allowedIds) {
                    return in_array($contact, $allowedIds);
                });

                $clientCase['clientContacts'] = $filteredContacts;
                $event->setData($clientCase);
            }

            $form->add('clientContacts', EntityType::class, [
                'class' => ClientContact::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => function($clientContact) {
                    return $clientContact->getFullName();
                },
                'attr' => [
                    'class' => 'pc-wrapper'
                ],
                "choices" => $client->getClientContacts()
            ]);
        } else {
            unset($clientCase['clientContacts']);
            $event->setData($clientCase);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientCase::class,
            'csrf_protection' => false
        ]);
    }
}