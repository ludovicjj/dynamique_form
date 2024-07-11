<?php

namespace App\Form\Type;

use App\Entity\Client;
use App\Entity\Country;
use App\Repository\CountryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function __construct(
        private readonly CountryRepository $countryRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Client $entity */
        $entity = $options['data'];
        $france = $this->countryRepository->findOneBy(['name' => 'France']);

        $builder
            ->add('companyName', TextType::class, [
                'label' => 'Nom de la société<span class="mandatory">*</span>',
                'label_html' => true
            ])
            ->add('address1', TextType::class, [
                'label' => 'Addresse<span class="mandatory">*</span>',
                'label_html' => true
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'Code Postal<span class="mandatory">*</span>',
                'label_html' => true
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville<span class="mandatory">*</span>',
                'label_html' => true
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone'
            ])
            ->add('siret', TextType::class)
            ->add('email', TextType::class)
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'label' => 'Pays<span class="mandatory">*</span>',
                'label_html' => true,
                'query_builder' => function(CountryRepository $er) {
                    return $er->findAllOrderByNameQueryBuilder();
                },
                'data' => $entity->getId() ? $entity->getCountry() : $france
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class
        ]);
    }
}