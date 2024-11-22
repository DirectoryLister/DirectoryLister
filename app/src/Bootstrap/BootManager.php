<?php

namespace App\Bootstrap;

use DI\Container;
use DI\ContainerBuilder;

class BootManager
{
    /** Create the application service container. */
    public static function createContainer(string $configPath, string $cachePath): Container
    {
        /** @var list<string> $configFiles */
        $configFiles = glob($configPath . '/*.php') ?: [];

        $container = (new ContainerBuilder)->addDefinitions(...$configFiles);

        if (self::containerCompilationEnabled()) {
            $container->enableCompilation($cachePath);
        }

        return $container->build();
    }

    /** Determine if container compilation should be enabled. */
    protected static function containerCompilationEnabled(): bool
    {
        if (filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOL)) {
            return false;
        }

        $compileContainer = getenv('COMPILE_CONTAINER');

        if ($compileContainer === false) {
            return true;
        }

        return strtolower($compileContainer) !== 'false';
    }
}
