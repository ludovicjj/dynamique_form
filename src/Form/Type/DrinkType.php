<?php

namespace App\Form\Type;

use App\Entity\Drink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class DrinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);
        $builder
            ->add('product', ChoiceType::class, [
                'choices' => [
                    'Choissisez un produit' => '',
                    'tea' => 'tea',
                    'coffee' => 'coffee'
                ],
                'expanded' => false,
                'multiple' => false,
                'autocomplete' => true
            ])

            ->addDependent('type', 'product', function (DependentField $field, ?string $product) {
                if ($product === 'tea') {
                    $field->add(ChoiceType::class, [
                        'choices' => [
                            'Green' => 'green',
                            'Black' => 'black',
                            'Herbal' => 'herbal'
                        ],
                        'expanded' => false,
                        'multiple' => false,
                        'autocomplete' => true,
                        'mapped' => false
                    ]);
                }

                if ($product === 'coffee') {
                    $field->add(ChoiceType::class, [
                        'choices' => [
                            'Espresso' => 'espresso',
                            'Americano' => 'americano',
                            'Latte' => 'latte'
                        ],
                        'expanded' => false,
                        'multiple' => false,
                        'autocomplete' => true,
                        'mapped' => false
                    ]);
                }
            })
            ->add('sugar', ChoiceType::class, [
                'choices' => [
                    'Avec sucre' => false,
                    'Sans sucre' => false,
                ],
                'expanded' => false,
                'multiple' => false,
                'autocomplete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Drink::class,
        ]);
    }
}
