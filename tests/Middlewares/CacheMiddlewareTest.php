<?php

namespace Tests\Middlewares;

use App\Middlewares\CacheMiddleware;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Tests\TestCase;

/** @covers \App\Middlewares\CacheMiddleware */
class CacheMiddlewareTest extends TestCase
{
    /** @var RequestHandlerInterface&MockObject */
    protected $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->handler = $this->createMock(RequestHandlerInterface::class);
    }

    public function test_it_adds_a_response_cache_header_with_age_of_zero_by_defualt(): void
    {
        $this->handler->expects($this->once())->method('handle')->willReturn(
            new Response(StatusCodeInterface::STATUS_OK, new Headers([
                'Content-Type' => 'text/html',
            ]))
        );

        $response = (new CacheMiddleware($this->config))($this->request, $this->handler);

        $this->assertEquals(['max-age=0'], $response->getHeader('Cache-Control'));
    }

    public function test_it_adds_a_response_cache_header_for_a_pre_configured_http_cache_option(): void
    {
        $this->handler->expects($this->once())->method('handle')->willReturn(
            new Response(StatusCodeInterface::STATUS_OK, new Headers([
                'Content-Type' => 'application/json',
            ]))
        );

        $response = (new CacheMiddleware($this->config))($this->request, $this->handler);

        $this->assertEquals(['max-age=300'], $response->getHeader('Cache-Control'));
    }
}
