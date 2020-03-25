<?php

use App\Factories;

return [
    'base_path' => dirname(__DIR__),
    'app_path' => __DIR__,
    'icons' => function (): array {
        return require __DIR__ . '/config/icons.php';
    },
    Symfony\Component\Finder\Finder::class => DI\factory(Factories\FinderFactory::class),
    Symfony\Contracts\Translation\TranslatorInterface::class => DI\factory(Factories\TranslationFactory::class),
    Slim\Views\Twig::class => DI\factory(Factories\TwigFactory::class),
    Whoops\RunInterface::class => DI\create(Whoops\Run::class),
];
