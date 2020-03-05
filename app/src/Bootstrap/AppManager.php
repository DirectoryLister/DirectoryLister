<?php

namespace App\Bootstrap;

use App\Exceptions\ExceptionManager;
use App\Middlewares;
use DI\Bridge\Slim\Bridge;
use DI\Container;
use Middlewares as HttpMiddlewares;
use Slim\App;
use Tightenco\Collect\Support\Collection;

class AppManager
{
    /** @const Array of application middlewares */
    protected const MIDDLEWARES = [
        Middlewares\WhoopsMiddleware::class
    ];

    /** @var Container The applicaiton container */
    protected $container;

    /**
     * Create a new AppManager object.
     *
     * @param \DI\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Setup and configure the application.
     *
     * @return \Slim\App
     */
    public function __invoke(): App
    {
        $this->container->call(ProviderManager::class);
        $app = Bridge::create($this->container);
        $this->registerMiddlewares($app);

        $this->container->call(ExceptionManager::class);

        return $app;
    }

    /**
     * Register application middlewares.
     *
     * @param \Slim\App $app
     *
     * @return void
     */
    protected function registerMiddlewares(App $app): void
    {
        Collection::make(self::MIDDLEWARES)->each(
            function (string $middleware) use ($app): void {
                $app->add($middleware);
            }
        );

        $app->add(new HttpMiddlewares\Expires([
            'application/zip' => '+1 hour',
            'text/json' => '+1 hour',
        ]));
    }
}
