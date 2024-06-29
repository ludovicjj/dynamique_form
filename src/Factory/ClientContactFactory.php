<?php

namespace App\Factory;

use App\Entity\ClientContact;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<ClientContact>
 */
final class ClientContactFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return ClientContact::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'firstname'     => self::faker()->firstName,
            'lastname'      => self::faker()->lastName,
            'phone'         => self::faker()->phoneNumber,
            'email'         => self::faker()->email,
            'client'        => ClientFactory::new(),
            'jobTitle'      => ClientJobTitleFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(ClientContact $clientContact): void {})
        ;
    }
}
