<?php

use App\Bootstrap\AppManager;
use App\Bootstrap\BootManager;
use Dotenv\Dotenv;

require __DIR__ . '/app/vendor/autoload.php';

// Initialize environment variable handler
Dotenv::createUnsafeImmutable(__DIR__)->safeLoad();

// Set file access restrictions
ini_set('open_basedir', implode(PATH_SEPARATOR, [__DIR__, getenv('FILES_PATH')]));

// Initialize the container
$container = BootManager::createContainer(
    __DIR__ . '/app/config',
    __DIR__ . '/app/cache'
);

// Initialize the application
$app = $container->call(AppManager::class);

// Engage!
$app->run();
