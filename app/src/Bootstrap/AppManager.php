<?php

namespace App\Bootstrap;

use DI\Bridge\Slim\Bridge;
use DI\Container;
use Slim\App;

class AppManager
{
    /** Create a new AppManager object. */
    public function __construct(
        private Container $container
    ) {}

    /** Setup and configure the application. */
    public function __invoke(): App
    {
        $app = Bridge::create($this->container);
        $this->container->call(MiddlewareManager::class);
        $this->container->call(ExceptionManager::class);
        $this->container->call(RouteManager::class);

        return $app;
    }
}
