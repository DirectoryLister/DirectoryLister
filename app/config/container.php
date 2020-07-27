<?php

use App\Factories;
use App\Middlewares;
use App\SortMethods;
use App\ViewFunctions;
use Middlewares as HttpMiddlewares;
use Psr\Container\ContainerInterface;

return [
    /** Path definitions */
    'base_path' => dirname(__DIR__, 2),
    'app_path' => dirname(__DIR__),
    'asset_path' => DI\string('{app_path}/assets'),
    'cache_path' => DI\string('{app_path}/cache'),
    'config_path' => DI\string('{app_path}/config'),
    'source_path' => DI\string('{app_path}/src'),
    'translations_path' => DI\string('{app_path}/translations'),
    'views_path' => DI\string('{app_path}/views'),

    /** Array of application files (to be hidden) */
    'app_files' => ['app', 'index.php', '.hidden'],

    /** Array of application middlewares */
    'middlewares' => function (ContainerInterface $container): array {
        return [
            Middlewares\WhoopsMiddleware::class,
            new HttpMiddlewares\Expires($container->get('http_expires')),
        ];
    },

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
    App\HiddenFiles::class => DI\factory([App\HiddenFiles::class, 'fromConfig']),
    Symfony\Component\Finder\Finder::class => DI\factory(Factories\FinderFactory::class),
    Symfony\Contracts\Cache\CacheInterface::class => DI\Factory(Factories\CacheFactory::class),
    Symfony\Contracts\Translation\TranslatorInterface::class => DI\factory(Factories\TranslationFactory::class),
    Slim\Views\Twig::class => DI\factory(Factories\TwigFactory::class),
    Whoops\RunInterface::class => DI\create(Whoops\Run::class),
];
