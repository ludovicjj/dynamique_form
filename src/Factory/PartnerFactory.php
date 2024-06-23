<?php

namespace App\Factory;

use App\Entity\Partner;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Partner>
 */
final class PartnerFactory extends PersistentProxyObjectFactory
{

    public static function class(): string
    {
        return Partner::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'address1' => self::faker()->address(),
            'city' => 'Paris',
            'companyName' => self::faker()->company(),
            'zipcode' => self::faker()->postcode,
            'phone' => self::faker()->phoneNumber,
            'email' => self::faker()->email,
            'siret' => '123456789',
            'country' => CountryFactory::new()
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Partner $partner): void {})
        ;
    }
}
