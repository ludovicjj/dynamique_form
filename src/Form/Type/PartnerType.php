<?php

namespace App\Form\Type;

use App\Entity\Country;
use App\Entity\Partner;
use App\Repository\CountryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('companyName', TextType::class, [
                'label' => "Compagnie<span class='mandatory'>*</span>",
                'label_html' => true
            ])
            ->add('address1', TextType::class, [
                'label' => "Adresse",
            ])
            ->add('zipcode', TextType::class, [
                'label' => "Code Postal",
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
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
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partner::class
        ]);
    }
}