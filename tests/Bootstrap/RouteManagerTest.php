<?php

declare(strict_types=1);

namespace Tests\Bootstrap;

use App\Bootstrap\RouteManager;
use App\Controllers;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Slim\App;
use Tests\TestCase;

#[CoversClass(RouteManager::class)]
class RouteManagerTest extends TestCase
{
    #[Test]
    public function it_registers_application_routes(): void
    {
        $app = $this->createMock(App::class);
        $app->expects($this->once())->method('get')->with(
            '/[{path:.*}]', Controllers\IndexController::class
        );

        (new RouteManager($app))();
    }
}
