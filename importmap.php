<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/js/app.js',
        'entrypoint' => true,
    ],
    'duck' => [
        'path' => './assets/js/duck.js',
    ],
    'client_case_show' => [
        'path' => './assets/styles/client_case_show.css',
    ],
    'bootstrap' => [
        'version' => '5.3.3',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.3',
        'type' => 'css',
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/ux-live-component' => [
        'path' => './vendor/symfony/ux-live-component/assets/dist/live_controller.js',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    'tom-select' => [
        'version' => '2.3.1',
    ],
    'tom-select/dist/css/tom-select.default.css' => [
        'version' => '2.3.1',
        'type' => 'css',
    ],
    '@easepick/core' => [
        'version' => '1.2.1',
    ],
    '@easepick/datetime' => [
        'version' => '1.2.1',
    ],
    '@easepick/lock-plugin' => [
        'version' => '1.2.1',
    ],
    '@easepick/base-plugin' => [
        'version' => '1.2.1',
    ],
    'stimulus-use' => [
        'version' => '0.52.2',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    'debounce' => [
        'version' => '2.1.0',
    ],
];
