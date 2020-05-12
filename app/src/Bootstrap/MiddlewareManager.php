<?php

namespace App\Bootstrap;

use DI\Container;
use Slim\App;

class MiddlewareManager
{
    /** @var App The application */
    protected $app;

    /** @var Container The application container */
    protected $container;

    /**
     * Create a new MiddlwareManager object.
     *
     * @param \Slim\App     $app
     * @param \DI\Container $container
     */
    public function __construct(App $app, Container $container)
    {
        $this->app = $app;
        $this->container = $container;
    }

    /**
     * Register application middlewares.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $this->container->get('middlewares')->each(
            function ($middleware): void {
                $this->app->add($middleware);
            }
        );
    }
}
