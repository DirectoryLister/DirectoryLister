<?php

declare(strict_types=1);

namespace Tests\Bootstrap;

use App\Bootstrap\MiddlewareManager;
use App\Middlewares;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Slim\App;
use Tests\TestCase;

#[CoversClass(MiddlewareManager::class)]
class MiddlewareManagerTest extends TestCase
{
    /** @const Array of application middlewares */
    private const MIDDLEWARES = [
        Middlewares\WhoopsMiddleware::class,
        Middlewares\PruneCacheMiddleware::class,
        Middlewares\CacheControlMiddleware::class,
        Middlewares\RegisterGlobalsMiddleware::class,
    ];

    #[Test]
    public function it_registers_application_middlewares(): void
    {
        $app = $this->createMock(App::class);
        $app->expects($matcher = $this->atLeast(1))->method('add')->willReturnCallback(
            function ($parameter) use ($matcher, $app): App {
                $this->assertSame(self::MIDDLEWARES[$matcher->numberOfInvocations() - 1], $parameter);

                return $app;
            }
        );

        (new MiddlewareManager($app, $this->config))();
    }
}
