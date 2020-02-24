<?php

namespace Tests\Exceptions;

use App\Exceptions\ErrorHandler;
use App\Exceptions\ExceptionManager;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

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
        $this->config->set('app.debug', true);

        $app = $this->createMock(App::class);
        $app->expects($this->never())->method('addErrorMiddleware');

        (new ExceptionManager($app, $this->config))();
    }
}
