<?php

use App\Bootstrap\AppManager;
use App\Controllers;
use DI\Container;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

// Set file access restrictions
ini_set('open_basedir', __DIR__);

// Initialize environment variable handler
Dotenv::createImmutable(__DIR__)->load();

// Initialize the container
$container = new Container();
$container->set('base_path', __DIR__);

// Configure the application
$app = $container->call(AppManager::class);

// Register routes
$app->get('/file-info/[{path:.*}]', Controllers\FileInfoController::class);
$app->get('/[{path:.*}]', Controllers\DirectoryController::class);

// Enagage!
$app->run();
