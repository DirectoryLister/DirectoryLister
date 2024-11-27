<?php

use App\Factories;
use App\Middlewares;
use App\SortMethods;
use App\ViewFunctions;
use function DI\create;
use function DI\factory;
use function DI\string;

return [
    /** Path definitions */
    'base_path' => dirname(__DIR__, 2),
    'app_path' => dirname(__DIR__),
    'asset_path' => string('{app_path}/assets'),
    'cache_path' => string('{app_path}/cache'),
    'config_path' => string('{app_path}/config'),
    'source_path' => string('{app_path}/src'),
    'translations_path' => string('{app_path}/translations'),
    'views_path' => string('{app_path}/views'),

    /** Array of application files (to be hidden) */
    'app_files' => ['app', 'index.php', '.analytics', '.env', '.env.example', '.hidden'],

    /** Array of application middlewares */
    'middlewares' => fn (): array => [
        Middlewares\WhoopsMiddleware::class,
        Middlewares\PruneCacheMiddleware::class,
        Middlewares\CacheControlMiddleware::class,
        Middlewares\RegisterGlobalsMiddleware::class,
    ],

    /** Array of sort options mapped to their respective classes */
    'sort_methods' => [
        'accessed' => SortMethods\Accessed::class,
        'changed' => SortMethods\Changed::class,
        'modified' => SortMethods\Modified::class,
        'name' => SortMethods\Name::class,
        'natural' => SortMethods\Natural::class,
        'type' => SortMethods\Type::class,
    ],

    /** Array of view functions */
    'view_functions' => [
        ViewFunctions\Analytics::class,
        ViewFunctions\Asset::class,
        ViewFunctions\Breadcrumbs::class,
        ViewFunctions\Config::class,
        ViewFunctions\FileUrl::class,
        ViewFunctions\Icon::class,
        ViewFunctions\Markdown::class,
        ViewFunctions\ModifiedTime::class,
        ViewFunctions\ParentUrl::class,
        ViewFunctions\SizeForHumans::class,
        ViewFunctions\Translate::class,
        ViewFunctions\Url::class,
        ViewFunctions\ZipUrl::class,
    ],

    /** Container definitions */
    App\HiddenFiles::class => factory([App\HiddenFiles::class, 'fromConfig']),
    Symfony\Component\Finder\Finder::class => factory(Factories\FinderFactory::class),
    Symfony\Contracts\Cache\CacheInterface::class => factory(Factories\CacheFactory::class),
    Symfony\Contracts\Translation\TranslatorInterface::class => factory(Factories\TranslationFactory::class),
    Slim\Views\Twig::class => factory(Factories\TwigFactory::class),
    Whoops\RunInterface::class => create(Whoops\Run::class),
];
