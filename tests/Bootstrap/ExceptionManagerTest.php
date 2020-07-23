<?php

namespace Tests\Bootstrap;

use App\Bootstrap\ExceptionManager;
use App\Exceptions\ErrorHandler;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

/** @covers \App\Bootstrap\ExceptionManager */
class ExceptionManagerTest extends TestCase
{
    public function test_it_sets_the_default_error_handler(): void
    {
        $errorMiddleware = $this->createMock(ErrorMiddleware::class);
        $errorMiddleware->expects($this->once())
            ->method('setDefaultErrorHandler')
            ->with(ErrorHandler::class);

        $app = $this->createMock(App::class);
        $app->expects($this->once())
            ->method('addErrorMiddleware')
            ->willReturn($errorMiddleware);

        (new ExceptionManager($app, $this->config))();
    }

    public function test_it_does_not_set_the_default_error_handler_when_debug_is_enabled(): void
    {
        $this->container->set('debug', true);

        $app = $this->createMock(App::class);
        $app->expects($this->never())->method('addErrorMiddleware');

        (new ExceptionManager($app, $this->config))();
    }
}
