<?php

namespace Tests\Bootstrap;

use App\Bootstrap\AppManager;
use Slim\App;
use Tests\TestCase;

/** @covers \App\Bootstrap\AppManager */
class AppManangerTest extends TestCase
{
    public function test_it_returns_an_app_instance(): void
    {
        $app = (new AppManager($this->container))($this->testFilesPath);

        $this->assertInstanceOf(App::class, $app);
        $this->assertSame($this->container, $app->getContainer());
    }
}
