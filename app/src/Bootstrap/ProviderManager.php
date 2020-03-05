<?php

namespace App\Bootstrap;

use App\Providers;
use DI\Container;
use Invoker\CallableResolver;
use Tightenco\Collect\Support\Collection;

class ProviderManager
{
    /** @const Array of application providers */
    protected const PROVIDERS = [
        Providers\ConfigProvider::class,
        Providers\FinderProvider::class,
        Providers\TranslationProvider::class,
        Providers\TwigProvider::class,
        Providers\WhoopsProvider::class,
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
     * Register application providers.
     *
     * @return void
     */
    public function __invoke(): void
    {
        Collection::make(self::PROVIDERS)->each(
            function (string $provider): void {
                $this->container->call(
                    $this->callableResolver->resolve($provider)
                );
            }
        );
    }
}
