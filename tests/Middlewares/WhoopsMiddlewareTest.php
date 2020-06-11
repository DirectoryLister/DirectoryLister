<?php

namespace Tests\Middlewares;

use App\Middlewares\WhoopsMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tests\TestCase;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\RunInterface;

/** @covers \App\Middlewares\WhoopsMiddleware */
class WhoopsMiddlewareTest extends TestCase
{
    public function test_it_registers_whoops_with_the_page_handler(): void
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

    public function test_it_registers_whoops_with_the_json_handler(): void
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
        $whoops->expects($this->exactly(2))->method('pushHandler')->withConsecutive(
            [$pageHandler],
            [$jsonHandler]
        );

        $middleware = new WhoopsMiddleware(
            $whoops, $pageHandler, $jsonHandler
        );

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())->method('getHeaderLine')->willReturn('application/json');

        $middleware($request, $this->createMock(RequestHandlerInterface::class));
    }
}
