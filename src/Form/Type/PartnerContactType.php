<?php

namespace App\Form\Type;

use App\Entity\PartnerContact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartnerContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => "Prénom<span class='mandatory'>*</span>",
                'label_html' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => "Nom<span class='mandatory'>*</span>",
                'label_html' => true
            ])
            ->add('phone', TextType::class, [
                'label' => "Téléphone"
            ])
            ->add('email', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PartnerContact::class
        ]);
    }
}