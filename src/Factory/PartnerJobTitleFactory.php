<?php

namespace App\Factory;

use App\Entity\PartnerJobTitle;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<PartnerJobTitle>
 */
final class PartnerJobTitleFactory extends PersistentProxyObjectFactory
{
    public const JOBS = [
        "MOA - Maître d'Ouvrage",
        "MOD - Maître d'Ouvrage Délégué",
        "AMO - Assistant Maître d'Ouvrage",
        "MOE",
        "Architecte",
        "Economiste",
        "OPC",
        "Géotechnicien",
        "BET - Bureaux d'études",
        "Thermicien",
        "Acousticien",
        "HQE",
        "Entreprise",
        "Laboratoire",
        "Coordinateur SPS",
        "Coordinateur SSI"
    ];

    public static function class(): string
    {
        return PartnerJobTitle::class;
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
            // ->afterInstantiate(function(PartnerJobTitle $partnerJobTitle): void {})
        ;
    }
}
