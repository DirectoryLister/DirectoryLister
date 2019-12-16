<?php

use App\Bootstrap\ConfigComposer;
use App\Bootstrap\FinderComposer;
use App\Bootstrap\ViewComposer;
use App\Controllers;
use DI\Bridge\Slim\Bridge;
use DI\Container;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

// Set file access restrictions
ini_set('open_basedir', __DIR__);

// Initialize environment variable handler
Dotenv::createImmutable(__DIR__)->load();

// Initialize the container
$container = new Container();
$container->set('app.root', __DIR__);

// Configure the application componentes
$container->call(ConfigComposer::class);
$container->call(FinderComposer::class);
$container->call(ViewComposer::class);

// Create the application
$app = Bridge::create($container);

// Register routes
$app->get('/[{path:.*}]', Controllers\DirectoryController::class);

// Enagage!
$app->run();
