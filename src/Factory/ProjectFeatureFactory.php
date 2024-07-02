<?php

namespace App\Factory;

use App\Entity\ProjectFeature;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<ProjectFeature>
 */
final class ProjectFeatureFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return ProjectFeature::class;
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
            // ->afterInstantiate(function(ProjectFeature $projectFeature): void {})
        ;
    }
}
