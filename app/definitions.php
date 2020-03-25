<?php

use App\Factories;
use Tightenco\Collect\Support\Collection;

return [
    'base_path' => dirname(__DIR__),
    'icons' => function () {
        return Collection::make(require 'app/config/icons.php');
    },
    Symfony\Component\Finder\Finder::class => DI\factory(Factories\FinderFactory::class),
    Symfony\Contracts\Translation\TranslatorInterface::class => DI\factory(Factories\TranslationFactory::class),
    Slim\Views\Twig::class => DI\factory(Factories\TwigFactory::class),
    Whoops\RunInterface::class => DI\create(Whoops\Run::class),
];
