<?php

use App\Controllers;
use DI\Container;
use DI\Bridge\Slim\Bridge;
use Dotenv\Dotenv;
use PHLAK\Config\Config;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Twig\Extension\CoreExtension;
use Twig\TwigFunction;

require __DIR__ . '/vendor/autoload.php';

/** Initialize environment variable handler */
$dotenv = Dotenv::create(__DIR__);
$dotenv->load();

/** Create the container */
$container = new Container();
$container->set(Config::class, $config = new Config('app/config'));

/** Set up our view handler */
$twig = new Twig("app/themes/{$config->get('theme')}", [
    'cache' => $config->get('view_cache')
]);

$twig->getEnvironment()->getExtension(CoreExtension::class)->setDateFormat(
    $config->get('date_format'), '%d days'
);

$twig->getEnvironment()->addFunction(
    new TwigFunction('asset', function ($path) use ($config) {
        return "/app/themes/{$config->get('theme')}/{$path}";
    })
);

$twig->getEnvironment()->addFunction(
    new TwigFunction('convertSize', function ($bytes) {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf('%.2f', $bytes / pow(1024, $factor)) . $sizes[$factor];
    })
);

$container->set(Twig::class, $twig);

/** Create the application */
$app = Bridge::create($container);
$app->add(TwigMiddleware::createFromContainer($app, Twig::class));
$app->get('/[{path:.*}]', Controllers\DirectoryController::class);

/** Enagage! */
$app->run();
