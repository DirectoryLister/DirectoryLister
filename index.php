<?php

use App\Bootstrap\AppManager;
use App\Support\Helpers;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

require __DIR__ . '/app/vendor/autoload.php';

// Set file access restrictions
ini_set('open_basedir', __DIR__);

// Initialize environment variable handler
Dotenv::createUnsafeImmutable(__DIR__)->safeLoad();

// Initialize the container
$container = (new ContainerBuilder)->addDefinitions(
    ...glob(__DIR__ . '/app/config/*.php')
);

// Compile the container
if (! Helpers::env('APP_DEBUG', false)) {
    $container->enableCompilation(__DIR__ . '/app/cache');
}

// Initialize the application
$app = $container->build()->call(AppManager::class);

// Engage!
$app->run();
