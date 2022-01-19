<?php

use App\Bootstrap\AppManager;
use App\Bootstrap\BootManager;
use Dotenv\Dotenv;
use Spatie\Ignition\Ignition;

require __DIR__ . '/app/vendor/autoload.php';

Ignition::make()->register();

// Set file access restrictions
ini_set('open_basedir', __DIR__);

// Initialize environment variable handler
Dotenv::createUnsafeImmutable(__DIR__)->safeLoad();

// Initialize the container
$container = BootManager::createContainer(
    __DIR__ . '/app/config',
    __DIR__ . '/app/cache'
);

// Initialize the application
$app = $container->call(AppManager::class);

// Engage!
$app->run();
