<?php

namespace App\Bootstrap;

use App\Exceptions\ExceptionManager;
use App\Middlewares;
use App\Providers;
use DI\Bridge\Slim\Bridge;
use DI\Container;
use Invoker\CallableResolver;
use Middlewares as HttpMiddlewares;
use Slim\App;
use Tightenco\Collect\Support\Collection;

class AppManager
{
    /** @const Array of application providers */
    protected const PROVIDERS = [
        Providers\ConfigProvider::class,
        Providers\FinderProvider::class,
        Providers\TwigProvider::class,
        Providers\WhoopsProvider::class,
    ];

    /** @const Array of application middlewares */
    protected const MIDDLEWARES = [
        Middlewares\WhoopsMiddleware::class
    ];

    /** @var Container The applicaiton container */
    protected $container;

    /** @var CallableResolver The callable resolver */
    protected $callableResolver;

    /**
     * Create a new AppManager object.
     *
     * @param \DI\Container             $container
     * @param \Invoker\CallableResolver $callableResolver
     */
    public function __construct(Container $container, CallableResolver $callableResolver)
    {
        $this->container = $container;
        $this->callableResolver = $callableResolver;
    }

    /**
     * Setup and configure the application.
     *
     * @return \Slim\App
     */
    public function __invoke(): App
    {
        $this->registerProviders();
        $app = Bridge::create($this->container);
        $this->registerMiddlewares($app);

        $this->container->call(ExceptionManager::class);

        return $app;
    }

    /**
     * Register application providers.
     *
     * @return void
     */
    protected function registerProviders(): void
    {
        Collection::make(self::PROVIDERS)->each(
            function (string $provider): void {
                $this->container->call(
                    $this->callableResolver->resolve($provider)
                );
            }
        );
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
