<?php

namespace App\Twig\Components\Core;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class SortTable
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly RequestStack $requestStack
    ) {
    }

    public string $routeName;
    public string $sortName;

    public string $property;

    public string $direction;

    public array $params = [];

    public function getUrl(): string
    {
        $request = $this->requestStack->getMainRequest();

        return $this->urlGenerator->generate($this->routeName, [
            ...$this->params,
            ...$request->query->all(),
            'sort' => $this->sortName,
            'sortDirection' =>  ($this->property === $this->sortName and $this->direction === 'asc') ? 'DESC' : 'ASC'
        ]);
    }

    public function getIcon(): string
    {
        if ($this->property === $this->sortName) {
            return  ($this->direction === 'asc') ? 'chevron-up' : 'chevron-down';
        } else {
            return 'chevron-up-down';
        }
    }
}