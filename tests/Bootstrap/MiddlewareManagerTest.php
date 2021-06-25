<?php

namespace Tests\Bootstrap;

use App\Bootstrap\MiddlewareManager;
use App\Middlewares;
use Slim\App;
use Tests\TestCase;

/** @covers \App\Bootstrap\MiddlewareManager */
class MiddlewareManagerTest extends TestCase
{
    /** @const Array of application middlewares */
    protected const MIDDLEWARES = [
        Middlewares\WhoopsMiddleware::class,
        Middlewares\PruneCacheMiddleware::class,
        Middlewares\CacheControlMiddleware::class,
        Middlewares\RegisterGlobalsMiddleware::class,
    ];

    public function test_it_registers_application_middlewares(): void
    {
        $arguments = array_map(static function (string $middleware): array {
            return [$middleware];
        }, self::MIDDLEWARES);

        $app = $this->createMock(App::class);
        $app->expects($this->atLeast(1))->method('add')->withConsecutive(...$arguments);

        (new MiddlewareManager($app, $this->config))();
    }
}
