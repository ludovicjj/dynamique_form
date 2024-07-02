<?php

namespace App\Factory;

use App\Entity\ClientCaseStatus;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<ClientCaseStatus>
 */
final class ClientCaseStatusFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return ClientCaseStatus::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(ClientCaseStatus $clientCaseStatus): void {})
        ;
    }
}
