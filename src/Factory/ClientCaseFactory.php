<?php

namespace App\Factory;

use App\Entity\ClientCase;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<ClientCase>
 */
final class ClientCaseFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    public static function class(): string
    {
        return ClientCase::class;
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
            'city' => self::faker()->city(),
            'projectName' => self::faker()->company(),
            'signedAt' => self::faker()->dateTimeBetween('-30 years', 'now', 'Europe/Paris'),
            'reference' => self::faker()->ean8(),
            'zipcode' => self::faker()->postcode(),
            'country' => CountryFactory::new()
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(ClientCase $clientCase): void {})
        ;
    }
}
