<?php

declare(strict_types=1);

use App\Factories;
use App\Filters;
use App\Functions;
use App\Managers;
use App\Middlewares;
use App\SortMethods;
use DI\Container;

use function DI\create;
use function DI\env;
use function DI\factory;
use function DI\get;
use function DI\string;
use function DI\value;

return [

    // -------------------------------------------------------------------------
    // Path definitions
    // -------------------------------------------------------------------------

    'base_path' => dirname(__DIR__, 2),
    'app_path' => dirname(__DIR__),
    'assets_path' => string('{app_path}/assets'),
    'cache_path' => string('{app_path}/cache'),
    'config_path' => string('{app_path}/config'),
    'files_path' => env('FILES_PATH', get('base_path')),
    'manifest_path' => string('{assets_path}/manifest.json'),
    'source_path' => string('{app_path}/src'),
    'translations_path' => string('{app_path}/translations'),
    'views_path' => string('{app_path}/views'),

    // -------------------------------------------------------------------------
    // Application managers
    // -------------------------------------------------------------------------

    'managers' => [
        Managers\MiddlewareManager::class,
        Managers\ExceptionManager::class,
        Managers\RouteManager::class,
    ],

    // -------------------------------------------------------------------------
    // Application middlewares
    // -------------------------------------------------------------------------

    'middlewares' => [
        Middlewares\WhoopsMiddleware::class,
        Middlewares\PruneCacheMiddleware::class,
        Middlewares\CacheControlMiddleware::class,
        Middlewares\RegisterGlobalsMiddleware::class,
    ],

    // -------------------------------------------------------------------------
    // View filters & functions
    // -------------------------------------------------------------------------

    'view_filters' => [
        Filters\Markdown::class,
    ],

    'view_functions' => [
        Functions\Analytics::class,
        Functions\Breadcrumbs::class,
        Functions\Config::class,
        Functions\FileUrl::class,
        Functions\Icon::class,
        Functions\Markdown::class,
        Functions\ModifiedTime::class,
        Functions\ParentUrl::class,
        Functions\SizeForHumans::class,
        Functions\Translate::class,
        Functions\Url::class,
        Functions\Vite::class,
        Functions\ZipUrl::class,
    ],

    // -------------------------------------------------------------------------
    // Directory sort options
    // -------------------------------------------------------------------------

    'sort_methods' => [
        'accessed' => SortMethods\Accessed::class,
        'changed' => SortMethods\Changed::class,
        'modified' => SortMethods\Modified::class,
        'name' => SortMethods\Name::class,
        'natural' => SortMethods\Natural::class,
        'type' => SortMethods\Type::class,
    ],

    // -------------------------------------------------------------------------
    // Container bindings
    // -------------------------------------------------------------------------

    App\HiddenFiles::class => factory([App\HiddenFiles::class, 'fromConfig']),
    League\CommonMark\ConverterInterface::class => factory(Factories\ConverterFactory::class),
    Symfony\Component\Finder\Finder::class => factory(Factories\FinderFactory::class),
    Symfony\Contracts\Cache\CacheInterface::class => factory(Factories\CacheFactory::class),
    Symfony\Contracts\Translation\TranslatorInterface::class => factory(Factories\TranslationFactory::class),
    Slim\Views\Twig::class => factory(Factories\TwigFactory::class),
    Whoops\RunInterface::class => create(Whoops\Run::class),

    /** Array of application files (to be hidden) */
    'app_files' => ['app', 'app/**', 'index.php', '.analytics', '.env', '.env.example', '.hidden'],

    /** Files path helper */
    'full_path' => value(static fn (string $path, Container $container): string => $container->get('files_path') . '/' . $path),

];
