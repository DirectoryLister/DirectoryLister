<?php

use App\Factories;
use App\Middlewares;
use App\SortMethods;
use App\ViewFunctions;
use Middlewares as HttpMiddlewares;
use Psr\Container\ContainerInterface;
use Tightenco\Collect\Support\Collection;

return [
    /** Path definitions */
    'base_path' => dirname(__DIR__, 2),
    'app_path' => dirname(__DIR__),
    'asset_path' => DI\string('{app_path}/assets'),
    'cache_path' => DI\string('{app_path}/cache'),
    'config_path' => DI\string('{app_path}/config'),
    'translations_path' => DI\string('{app_path}/translations'),
    'views_path' => DI\string('{app_path}/views'),
    'icons_config' => DI\string('{config_path}/icons.php'),

    /** Array of application files (to be hidden) */
    'app_files' => ['app', 'index.php', '.hidden'],

    /** Collection of application middlewares */
    'middlewares' => function (ContainerInterface $container): Collection {
        return Collection::make([
            Middlewares\WhoopsMiddleware::class,
            new HttpMiddlewares\Expires($container->get('http_expires')),
        ]);
    },

    /** Collection of sort options mapped to their respective classes */
    'sort_methods' => function (): Collection {
        return Collection::make([
            'accessed' => SortMethods\Accessed::class,
            'changed' => SortMethods\Changed::class,
            'modified' => SortMethods\Modified::class,
            'name' => SortMethods\Name::class,
            'natural' => SortMethods\Natural::class,
            'type' => SortMethods\Type::class,
        ]);
    },

    /** Collection of available translation languages */
    'translations' => function (): Collection {
        return Collection::make([
            'de', 'en', 'es', 'fr', 'id', 'it', 'kr', 'nl',
            'pl', 'pt-BR', 'ro', 'ru', 'zh-CN', 'zh-TW'
        ]);
    },

    /** Array of view functions */
    'view_functions' => [
        ViewFunctions\Asset::class,
        ViewFunctions\Breadcrumbs::class,
        ViewFunctions\Config::class,
        ViewFunctions\FileUrl::class,
        ViewFunctions\Icon::class,
        ViewFunctions\Markdown::class,
        ViewFunctions\ParentUrl::class,
        ViewFunctions\SizeForHumans::class,
        ViewFunctions\Translate::class,
        ViewFunctions\Url::class,
        ViewFunctions\ZipUrl::class,
    ],

    /** Container definitions */
    Symfony\Component\Finder\Finder::class => DI\factory(Factories\FinderFactory::class),
    Symfony\Contracts\Translation\TranslatorInterface::class => DI\factory(Factories\TranslationFactory::class),
    Slim\Views\Twig::class => DI\factory(Factories\TwigFactory::class),
    Whoops\RunInterface::class => DI\create(Whoops\Run::class),
];
