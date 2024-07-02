<?php

namespace App\Factory;

use App\Entity\UserJobTitle;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<UserJobTitle>
 */
final class UserJobTitleFactory extends PersistentProxyObjectFactory
{

    public static function class(): string
    {
        return UserJobTitle::class;
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
            // ->afterInstantiate(function(UserJobTitle $userJobTitle): void {})
        ;
    }
}
