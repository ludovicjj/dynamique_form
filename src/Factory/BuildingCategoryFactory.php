<?php

namespace App\Factory;

use App\Entity\BuildingCategory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<BuildingCategory>
 */
final class BuildingCategoryFactory extends PersistentProxyObjectFactory
{

    public static function class(): string
    {
        return BuildingCategory::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'code' => self::faker()->text(255),
            'description' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(BuildingCategory $buildingCategory): void {})
        ;
    }
}
