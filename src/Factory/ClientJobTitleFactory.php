<?php

namespace App\Factory;

use App\Entity\ClientJobTitle;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<ClientJobTitle>
 */
final class ClientJobTitleFactory extends PersistentProxyObjectFactory
{
    public const JOBS = [
        "MOA - Maître d'Ouvrage"
    ];

    public static function class(): string
    {
        return ClientJobTitle::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->randomElement(self::JOBS),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(ClientJobTitle $clientJobTitle): void {})
        ;
    }
}
