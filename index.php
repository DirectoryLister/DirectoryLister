<?php

use App\Bootstrap\AppManager;
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

// Initialize the application
$app = $container->build()->call(AppManager::class);

// Engage!
$app->run();
