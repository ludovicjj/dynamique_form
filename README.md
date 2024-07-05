# LiveComponent

Veille sur les composants: AssetMapper, Symfony UX(Live Components, Twig Components, ux-icons, Stimulus) de symfony a travers un projet test de gestion de porte feuille d'affaire et de création de document administratif.


## Config
- symfony 7.1.*
- mysql(docker)
- doctrine 3.2


## How to use
 Requirement
- docker
- php 8.3
- symfony cli


### 1 - clone repository
```
git clone git@github.com:ludovicjj/dynamique_form.git
```
### 2 - install dependencies
```
composer install
symfony console importmap:install
```
### 3 - Load migrations/data
```
docker compose up -d (start docker)
symfony console d:m:m (run migrations)
symfony console app:import-data (load fixtures)
```

## usefully link

- [SymfonyCasts/dynamic-forms](https://github.com/SymfonyCasts/dynamic-forms)
- [Auto-Validating Form](https://ux.symfony.com/demos/live-component/auto-validating-form)
- [Problèmes de rendu de formulaire - PasswordType](https://symfony.com/bundles/ux-live-component/current/index.html#form-rendering-problems)
- [DEMO](https://ux.symfony.com/demos)
- [stimulus](https://symfony.com/bundles/StimulusBundle/current/index.html)
- [Live-Component](https://symfony.com/bundles/ux-live-component/current/index.html)
- [Twig-Component](https://symfony.com/bundles/ux-twig-component/current/index.html)

## fil d'actualité (GITHUB)
- [Embeded stimulus controller in a live form not working on re-render](https://github.com/symfony/ux/issues/489)
- [Better control over re-rendering behavior](https://github.com/symfony/ux/issues/490)
- [AssetMapper - load of specific CSS files](https://github.com/symfony/symfony/issues/51329)
- [ erreurs de formulaire personnalisées](https://github.com/symfony/ux/issues/1123)

## Usefully command

```
symfony console make:docker:database
symfony var:export --multiline 
```

## a lire
[dispatch event from stimulus](https://symfony.com/bundles/ux-live-component/current/index.html#emitting-an-event)
