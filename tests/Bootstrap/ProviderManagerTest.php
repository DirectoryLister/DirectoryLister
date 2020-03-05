<?php

namespace Tests\Bootstrap;

use App\Bootstrap\ProviderManager;
use App\Providers;
use DI\Container;
use Invoker\CallableResolver;
use Tests\TestCase;

class ProviderManagerTest extends TestCase
{
    /** @const Array of application providers */
    protected const PROVIDERS = [
        Providers\ConfigProvider::class,
        Providers\FinderProvider::class,
        Providers\TranslationProvider::class,
        Providers\TwigProvider::class,
        Providers\WhoopsProvider::class,
    ];

    public function test_it_registers_the_application_providers(): void
    {
        $callableResolver = $this->container->get(CallableResolver::class);

        $arguments = array_map(function (string $provider) use ($callableResolver): array {
            return [$callableResolver->resolve($provider)];
        }, self::PROVIDERS);

        $container = $this->createMock(Container::class);
        $container->expects($this->atLeast(4))->method('call')
            ->withConsecutive(...$arguments);

        (new ProviderManager($container, $callableResolver))();
    }
}
