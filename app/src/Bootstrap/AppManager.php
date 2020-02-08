<?php

namespace App\Bootstrap;

use App\Providers;
use DI\Bridge\Slim\Bridge;
use DI\Container;
use Invoker\CallableResolver;
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

    /** @var Container The applicaiton container */
    protected $container;

    /** @var CallableResolver The callable resolver */
    protected $callableResolver;

    /**
     * Create a new Provider object.
     *
     * @param \DI\Container $container
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

        return Bridge::create($this->container);
    }

    /**
     * Register application providers.
     *
     * @return void
     */
    protected function registerProviders(): void
    {
        Collection::make(self::PROVIDERS)->each(
            function (string $provider) {
                $this->container->call(
                    $this->callableResolver->resolve($provider)
                );
            }
        );
    }
}
