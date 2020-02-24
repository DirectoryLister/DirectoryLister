<?php

namespace Tests\Bootstrap;

use App\Bootstrap\AppManager;
use App\Providers;
use DI\Container;
use Invoker\CallableResolver;
use Slim\App;
use Tests\TestCase;

class AppManangerTest extends TestCase
{
    public function test_it_returns_an_app_instance(): void
    {
        $callableResolver = $this->container->get(CallableResolver::class);
        $app = (new AppManager($this->container, $callableResolver))();

        $this->assertInstanceOf(App::class, $app);
    }

    public function test_it_registeres_providers(): void
    {
        $callableResolver = $this->container->get(CallableResolver::class);

        $container = $this->createMock(Container::class);
        $container->expects($this->atLeast(4))->method('call')->withConsecutive(
            [$callableResolver->resolve(Providers\ConfigProvider::class)],
            [$callableResolver->resolve(Providers\FinderProvider::class)],
            [$callableResolver->resolve(Providers\TwigProvider::class)],
            [$callableResolver->resolve(Providers\WhoopsProvider::class)],
        );

        (new AppManager($container, $callableResolver))();
    }
}
