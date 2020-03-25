<?php

namespace App\Bootstrap;

use App\Middlewares;
use DI\Container;
use Middlewares as HttpMiddlewares;
use Slim\App;
use Tightenco\Collect\Support\Collection;

class MiddlewareManager
{
    /** @const Array of application middlewares */
    protected const MIDDLEWARES = [
        Middlewares\WhoopsMiddleware::class
    ];

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
    public function __invoke()
    {
        Collection::make(self::MIDDLEWARES)->each(
            function (string $middleware): void {
                $this->app->add($middleware);
            }
        );

        $this->app->add(new HttpMiddlewares\Expires(
            $this->container->get('http_expires')
        ));
    }
}
