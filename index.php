<?php

use App\Bootstrap\ViewComposer;
use App\Controllers;
use DI\Container;
use DI\Bridge\Slim\Bridge;
use Dotenv\Dotenv;
use PHLAK\Config\Config;
use Slim\Views\Twig;

require __DIR__ . '/vendor/autoload.php';

/** Initialize environment variable handler */
$dotenv = Dotenv::create(__DIR__);
$dotenv->load();

/** Create the container */
$container = new Container();

/** Register dependencies */
$container->set(Config::class, new Config('app/config'));
$container->set(Twig::class, function (Config $config) {
    return new Twig("app/themes/{$config->get('theme')}", [
        'cache' => $config->get('view_cache')
    ]);
});

/** Configure the view handler */
$container->call(ViewComposer::class);

/** Create the application */
$app = Bridge::create($container);

/** Register routes */
$app->get('/[{path:.*}]', Controllers\DirectoryController::class);

/** Enagage! */
$app->run();
