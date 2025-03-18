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
        $pageHandler = $this->createMock(PrettyPageHandler::class);
        $pageHandler->expects($this->once())->method('getPageTitle')->willReturn(
            'Test title; please ignore'
        );
        $pageHandler->expects($this->once())->method('setPageTitle')->with(
            'Test title; please ignore • Directory Lister'
        );

        $whoops = $this->createMock(RunInterface::class);
        $whoops->expects($this->once())->method('pushHandler')->with(
            $pageHandler
        );

        $middleware = new WhoopsMiddleware(
            $whoops, $pageHandler, new JsonResponseHandler
        );

        $middleware(
            $this->createMock(ServerRequestInterface::class),
            $this->createMock(RequestHandlerInterface::class)
        );
    }

    #[Test]
    public function it_registers_whoops_with_the_json_handler(): void
    {
        $pageHandler = $this->createMock(PrettyPageHandler::class);
        $pageHandler->expects($this->once())->method('getPageTitle')->willReturn(
            'Test title; please ignore'
        );
        $pageHandler->expects($this->once())->method('setPageTitle')->with(
            'Test title; please ignore • Directory Lister'
        );

        $jsonHandler = new JsonResponseHandler;

        $whoops = $this->createMock(RunInterface::class);
        $whoops->expects($matcher = $this->exactly(2))->method('pushHandler')->willReturnCallback(
            fn (Handler $parameter) => match ($matcher->numberOfInvocations()) {
                1 => $this->assertSame($pageHandler, $parameter),
                2 => $this->assertSame($jsonHandler, $parameter),
                default => $this->fail('Unexpected invocation')
            }
        );

        $middleware = new WhoopsMiddleware($whoops, $pageHandler, $jsonHandler);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())->method('getHeaderLine')->willReturn('application/json');

        $middleware($request, $this->createMock(RequestHandlerInterface::class));
    }
}
