<?php

namespace App\Bootstrap;

use App\Middleware;
use App\Providers;
use DI\Bridge\Slim\Bridge;
use DI\Container;
use Invoker\CallableResolver;
use PHLAK\Config\Config;
use Slim\App;
use Tightenco\Collect\Support\Collection;

class AppManager
{
    /** @const Array of application providers */
    protected const PROVIDERS = [
        Providers\ConfigProvider::class,
        Providers\FinderProvider::class,
        Providers\TwigProvider::class,
    ];

    /** @const Constant description */
    protected const MIDDLEWARES = [
        Middleware\StripBasePathMiddleware::class,
    ];

    /** @var Container The applicaiton container */
    protected $container;

    /** @var Config The application config */
    protected $config;

    /** @var CallableResolver The callable resolver */
    protected $callableResolver;

    /**
     * Create a new Provider object.
     *
     * @param \DI\Container $container
     */
    public function __construct(Container $container, Config $config, CallableResolver $callableResolver)
    {
        $this->container = $container;
        $this->config = $config;
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

        return $app;
    }

    /**
     * Register application providers.
     *
     * @return void
     */
    protected function registerProviders(): void
    {
        Collection::make(self::PROVIDERS)->merge(
            $this->config->get('app.providers', [])
        )->each(function (string $provider) {
            $this->container->call(
                $this->callableResolver->resolve($provider)
            );
        });
    }

    /**
     * Register application middleware.
     *
     * @param \Slim\App $app
     *
     * @return void
     */
    protected function registerMiddlewares(App $app): void
    {
        Collection::make(self::MIDDLEWARES)->merge(
            $this->config->get('app.middlewares', [])
        )->each(function (string $middleware) use ($app) {
            $app->add($middleware);
        });
    }
}
