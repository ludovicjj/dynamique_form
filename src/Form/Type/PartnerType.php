<?php

namespace App\Form\Type;

use App\Entity\Country;
use App\Entity\Partner;
use App\Entity\PartnerJobTitle;
use App\Repository\CountryRepository;
use App\Repository\PartnerJobTitleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartnerType extends AbstractType
{
    public function __construct(
        private readonly CountryRepository $countryRepository,
        private readonly PartnerJobTitleRepository $partnerJobTitleRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ?Partner $entity */
        $entity = $options['data'] ?? null;
        $france = $this->countryRepository->findOneBy(['name' => 'France']);
        $defaultJobTitle = $this->partnerJobTitleRepository->findOneBy(['name' => "MOA - Maître d'Ouvrage"]);

        $builder
            ->add('companyName', TextType::class, [
                'label' => "Nom de la société<span class='mandatory'>*</span>",
                'label_html' => true
            ])
            ->add('address1', TextType::class, [
                'label' => "Adresse<span class='mandatory'>*</span>",
                'label_html' => true
            ])
            ->add('zipcode', TextType::class, [
                'label' => "Code Postal<span class='mandatory'>*</span>",
                'label_html' => true
            ])
            ->add('city', TextType::class, [
                'label' => "Ville<span class='mandatory'>*</span>",
                'label_html' => true
            ])
            ->add('jobTitle', EntityType::class, [
                'class' => PartnerJobTitle::class,
                'choice_label' => 'name',
                'label' => "Fonction<span class='mandatory'>*</span>",
                'label_html' => true,
                'data' => $entity ? $entity->getJobTitle() : $defaultJobTitle
            ])
            ->add('siret', TextType::class)
            ->add('phone', TextType::class, [
                'label' => "Téléphone",
            ])
            ->add('email', TextType::class, [
                'label' => "Email",
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'label' => "Pays<span class='mandatory'>*</span>",
                'label_html' => true,
                'query_builder' => function(CountryRepository $er) {
                    return $er->findAllOrderByNameQueryBuilder();
                },
                'data' => $entity ? $entity->getCountry() : $france
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partner::class
        ]);
    }
}