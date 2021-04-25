<?php

namespace App\Bootstrap;

use DI\Container;
use DI\ContainerBuilder;

class BootManager
{
    /** Create the application service container. */
    public static function createContainer(string $configPath, string $cachePath): Container
    {
        $container = (new ContainerBuilder)->addDefinitions(
            ...glob($configPath . '/*.php')
        );

        if (self::enableContainerCompilation()) {
            $container->enableCompilation($cachePath);
        }

        return $container->build();
    }

    /** Determine if container compilation should be enabled. */
    protected static function enableContainerCompilation(): bool
    {
        if (filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOL)) {
            return false;
        }

        if (! filter_var(getenv('COMPILE_CONTAINER'), FILTER_VALIDATE_BOOL)) {
            return false;
        }

        return true;
    }
}
