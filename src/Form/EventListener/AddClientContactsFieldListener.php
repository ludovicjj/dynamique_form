<?php

namespace App\Form\EventListener;

use App\Entity\Client;
use App\Entity\ClientCase;
use App\Entity\ClientContact;
use App\Repository\ClientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\PreSubmitEvent;

class AddClientContactsFieldListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly ClientRepository $clientRepository
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
        ];
    }

    public function onPreSetData(PreSetDataEvent $event): void
    {
        /** @var ClientCase $clientCase */
        $clientCase = $event->getData();
        $form = $event->getForm();


        if ($clientCase->getClient()) {
            $client = $clientCase->getClient();

            $form->add('clientContacts', EntityType::class, [
                'class' => ClientContact::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => function($clientContact) {
                    return $clientContact->getFullName();
                },
                'attr' => [
                    'class' => 'choice-wrapper choice-wrapper-3'
                ],
                "choices" => $client->getClientContacts()
            ]);
        }
    }

    public function onPreSubmit(PreSubmitEvent $event): void
    {
        $clientCase = $event->getData();
        $form = $event->getForm();

        if (!$clientCase) {
            return;
        }

        if (isset($clientCase['client']) && $clientCase['client']) {
            $client = $this->clientRepository->find($clientCase['client']);

            if (!$client) {
                return;
            }

            $this->filterClientContactsChoices($clientCase, $client, $event);

            $form->add('clientContacts', EntityType::class, [
                'class' => ClientContact::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => function($clientContact) {
                    return $clientContact->getFullName();
                },
                'attr' => [
                    'class' => 'choice-wrapper choice-wrapper-3'
                ],
                "choices" => $client->getClientContacts()
            ]);
        } else {
            unset($clientCase['clientContacts']);
            $event->setData($clientCase);
        }
    }

    private function filterClientContactsChoices(array $clientCase, Client $client, PreSubmitEvent $event): void
    {
        if (!isset($clientCase['clientContacts'])) {
            return;
        }

        $allowedIds = $client->getClientContacts()->map(function ($contact) {
            return $contact->getId();
        })->toArray();

        $filteredContacts = array_filter($clientCase['clientContacts'], function($contact) use ($allowedIds) {
            return in_array($contact, $allowedIds);
        });

        $clientCase['clientContacts'] = $filteredContacts;
        $event->setData($clientCase);
    }
}