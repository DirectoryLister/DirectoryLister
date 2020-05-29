<?php

use App\Bootstrap\AppManager;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

require __DIR__ . '/app/vendor/autoload.php';

// Set file access restrictions
ini_set('open_basedir', __DIR__);

// Initialize environment variable handler
Dotenv::createImmutable(__DIR__)->safeLoad();

// Initialize the application
$app = (new ContainerBuilder)->addDefinitions(
    __DIR__ . '/app/config/cache.php',
    __DIR__ . '/app/config/app.php',
    __DIR__ . '/app/config/container.php'
)->build()->call(AppManager::class);

// Engage!
$app->run();
