<?php

declare(strict_types=1);

use App\Bootstrap\Builder;
use Dotenv\Dotenv;

require __DIR__ . '/app/vendor/autoload.php';

// Initialize environment variable handler
Dotenv::createUnsafeImmutable(__DIR__)->safeLoad();

// Set file access restrictions
ini_set('open_basedir', implode(PATH_SEPARATOR, [__DIR__, getenv('FILES_PATH')]));

// Create the DI container
$container = Builder::createContainer(
    __DIR__ . '/app/config',
    __DIR__ . '/app/cache'
);

// Create the application
$app = Builder::createApp($container);

// Engage!
$app->run();
