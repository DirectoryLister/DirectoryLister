<?php

namespace Tests\Bootstrap;

use App\Bootstrap\AppManager;
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
}
