<?php

declare(strict_types=1);

namespace Tests\Middlewares;

use App\Middlewares\WhoopsMiddleware;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tests\TestCase;
use Whoops\Handler\Handler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\RunInterface;

#[CoversClass(WhoopsMiddleware::class)]
class WhoopsMiddlewareTest extends TestCase
{
    #[Test]
    public function it_registers_whoops_with_the_page_handler(): void
    {
        $this->container->set('debug', 'true');

        $pageHandler = $this->mock(PrettyPageHandler::class);
        $pageHandler->expects($this->once())->method('getPageTitle')->willReturn(
            'Test title; please ignore'
        );

        $pageHandler->expects($this->once())->method('setPageTitle')->with(
            'Test title; please ignore • Directory Lister'
        );

        $whoops = $this->mock(RunInterface::class);
        $whoops->expects($this->once())->method('pushHandler')->with(
            $pageHandler
        );

        $this->container->call(WhoopsMiddleware::class, [
            'request' => $this->createMock(ServerRequestInterface::class),
            'handler' => $this->createMock(RequestHandlerInterface::class),
        ]);
    }

    #[Test]
    public function it_registers_whoops_with_the_json_handler(): void
    {
        $this->container->set('debug', 'true');

        $pageHandler = $this->mock(PrettyPageHandler::class);
        $pageHandler->expects($this->once())->method('getPageTitle')->willReturn(
            'Test title; please ignore'
        );
        $pageHandler->expects($this->once())->method('setPageTitle')->with(
            'Test title; please ignore • Directory Lister'
        );

        $jsonHandler = $this->mock(JsonResponseHandler::class);

        $whoops = $this->mock(RunInterface::class);
        $whoops->expects($matcher = $this->exactly(2))->method('pushHandler')->willReturnCallback(
            fn (Handler $parameter) => match ($matcher->numberOfInvocations()) {
                1 => $this->assertSame($pageHandler, $parameter),
                2 => $this->assertSame($jsonHandler, $parameter),
                default => $this->fail('Unexpected invocation')
            }
        );

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())->method('getHeaderLine')->willReturn('application/json');

        $this->container->call(WhoopsMiddleware::class, [
            'request' => $request,
            'handler' => $this->createMock(RequestHandlerInterface::class),
        ]);
    }

    #[Test]
    public function it_does_not_register_whoops_when_debug_is_disabled(): void
    {
        $this->container->set('debug', 'false');

        $pageHandler = $this->mock(PrettyPageHandler::class);
        $pageHandler->expects($this->never())->method('setPageTitle');

        $whoops = $this->mock(RunInterface::class);
        $whoops->expects($this->never())->method('pushHandler');

        $this->container->call(WhoopsMiddleware::class, [
            'request' => $this->createMock(ServerRequestInterface::class),
            'handler' => $this->createMock(RequestHandlerInterface::class),
        ]);
    }
}
