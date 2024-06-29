<?php

namespace App\Form\Type;

use App\Entity\ClientContact;
use App\Entity\ClientJobTitle;
use App\Repository\ClientJobTitleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientContactType extends AbstractType
{
    public function __construct(
        private readonly ClientJobTitleRepository $clientJobTitleRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ?ClientContact $entity */
        $entity = $options['data'] ?? null;
        $defaultJobTitle = $this->clientJobTitleRepository->findOneBy(['name' => "MOA - Maître d'Ouvrage"]);

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
            ->add('email', TextType::class)
            ->add('jobTitle', EntityType::class, [
                'class' => ClientJobTitle::class,
                'choice_label' => 'name',
                'label' => "Fonction<span class='mandatory'>*</span>",
                'label_html' => true,
                'data' => $entity ? $entity->getJobTitle() : $defaultJobTitle
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientContact::class
        ]);
    }
}