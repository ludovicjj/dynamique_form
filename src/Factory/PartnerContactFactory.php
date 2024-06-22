<?php

namespace App\Factory;

use App\Entity\PartnerContact;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<PartnerContact>
 */
final class PartnerContactFactory extends PersistentProxyObjectFactory
{

    public static function class(): string
    {
        return PartnerContact::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'firstname' => self::faker()->name,
            'lastname' => self::faker()->lastName,
            'phone' => self::faker()->phoneNumber,
            'email' => self::faker()->email,
            'partner' => PartnerFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(PartnerContact $partnerContact): void {})
        ;
    }
}
