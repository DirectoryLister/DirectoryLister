<?php

declare(strict_types=1);

namespace Tests\Bootstrap;

use App\Bootstrap\AppManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Slim\App;
use Tests\TestCase;

#[CoversClass(AppManager::class)]
class AppManagerTest extends TestCase
{
    #[Test]
    public function it_returns_an_app_instance(): void
    {
        $app = (new AppManager($this->container))();

        $this->assertInstanceOf(App::class, $app);
        $this->assertSame($this->container, $app->getContainer());
    }
}
