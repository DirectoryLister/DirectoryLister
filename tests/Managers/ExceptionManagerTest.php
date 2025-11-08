<?php

declare(strict_types=1);

namespace Tests\Managers;

use App\Exceptions\ErrorHandler;
use App\Managers\ExceptionManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

#[CoversClass(ExceptionManager::class)]
class ExceptionManagerTest extends TestCase
{
    #[Test]
    public function it_sets_the_default_error_handler(): void
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

    #[Test]
    public function it_does_not_set_the_default_error_handler_when_debug_is_enabled(): void
    {
        $this->container->set('debug', true);

        $app = $this->createMock(App::class);
        $app->expects($this->never())->method('addErrorMiddleware');

        (new ExceptionManager($app, $this->config))();
    }
}
