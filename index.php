<?php

use App\Bootstrap\FilesComposer;
use App\Bootstrap\ViewComposer;
use App\Controllers;
use DI\Bridge\Slim\Bridge;
use DI\Container;
use Dotenv\Dotenv;
use PHLAK\Config\Config;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;

require __DIR__ . '/vendor/autoload.php';

/** Set file access restrictions */
ini_set('open_basedir', __DIR__);

/** Initialize environment variable handler */
Dotenv::createImmutable(__DIR__)->load();

/** Create the container */
$container = new Container();

/** Register dependencies */
$container->set(Config::class, new Config('app/config'));
$container->set(Finder::class, new Finder());
$container->set(Twig::class, function (Config $config) {
    return new Twig("app/themes/{$config->get('theme', 'default')}");
});

/** Configure the application components */
$container->call(FilesComposer::class);
$container->call(ViewComposer::class);

/** Create the application */
$app = Bridge::create($container);

/** Register routes */
$app->get('/[{path:.*}]', Controllers\DirectoryController::class);

/** Enagage! */
$app->run();
