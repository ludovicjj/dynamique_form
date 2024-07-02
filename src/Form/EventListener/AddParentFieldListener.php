<?php

namespace App\Form\EventListener;

use App\Entity\ClientCase;
use App\Entity\ClientContact;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormEvents;

class AddParentFieldListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
        ];
    }

    public function onPreSubmit(PreSubmitEvent $event): void
    {
        $clientCase = $event->getData();
        $form = $event->getForm();

        if (!$clientCase) {
            return;
        }

        if (isset($clientCase['isChild']) && $clientCase['isChild'] === '1') {
            $form->add('parent', EntityType::class, [
                'class' => ClientCase::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' => function($clientCase) {
                    return $clientCase->getProjectName();
                },
                'attr' => [
                    'class' => 'choice-wrapper choice-wrapper-3'
                ],
            ]);
        } else {
            unset($clientCase['parent']);
            $event->setData($clientCase);
        }
    }
}