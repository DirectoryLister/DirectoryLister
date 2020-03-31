<?php

use App\Factories;

return [
    /** Path definitions */
    'app_path' => dirname(__DIR__),
    'base_path' => dirname(__DIR__, 2),
    'config_path' => DI\string('{app_path}/config'),
    'icons_config' => DI\string('{config_path}/icons.php'),

    /** Container definitions */
    Symfony\Component\Finder\Finder::class => DI\factory(Factories\FinderFactory::class),
    Symfony\Contracts\Translation\TranslatorInterface::class => DI\factory(Factories\TranslationFactory::class),
    Slim\Views\Twig::class => DI\factory(Factories\TwigFactory::class),
    Whoops\RunInterface::class => DI\create(Whoops\Run::class),
];
