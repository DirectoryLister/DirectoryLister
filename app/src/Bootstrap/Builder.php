<?php

declare(strict_types=1);

namespace App\Bootstrap;

use DI\Bridge\Slim\Bridge;
use DI\Container;
use DI\ContainerBuilder;
use Slim\App;

class Builder
{
    public static function createContainer(string $configPath, string $cachePath): Container
    {
        /** @var list<string> $configFiles */
        $configFiles = glob($configPath . '/*.php') ?: [];

        $containerBuilder = (new ContainerBuilder)->useAttributes(true)->addDefinitions(...$configFiles);

        if (self::containerCompilationEnabled()) {
            $containerBuilder->enableCompilation($cachePath);
        }

        return $containerBuilder->build();
    }

    /** @return App<Container> */
    public static function createApp(Container $container): App
    {
        $app = Bridge::create($container);

        foreach ((array) $container->get('managers') as $manager) {
            /** @var callable $manager */
            $container->call($manager);
        }

        return $app;
    }

    private static function containerCompilationEnabled(): bool
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
