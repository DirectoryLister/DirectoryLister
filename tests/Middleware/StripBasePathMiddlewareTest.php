<?php

namespace Tests\Middleware;

use App\Middleware\StripBasePathMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Stream;
use Slim\Psr7\Uri;
use Tests\TestCase;

class StripBasePathMiddlewareTest extends TestCase
{
    public function test_the_path_is_unchanged_for_a_request_in_the_webroot(): void
    {
        $middleware = new StripBasePathMiddleware($this->container);

        $uri = new Uri('http', 'localhost', null, '/foo/bar');
        $request = new Request('GET', $uri, new Headers, [], [], new Stream(fopen('php://memory', 'w+')));

        $handler = $this->createMock(App::class);
        $handler->expects($this->once())->method('handle')->with(
            $this->callback(function (ServerRequestInterface $request): bool {
                return $request->getUri()->getPath() == '/foo/bar';
            })
        );

        $middleware($request, $handler);
    }

    public function test_it_strips_the_base_path_for_a_request_in_a_subdirectory(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/some/dir/index.php';

        $middleware = new StripBasePathMiddleware($this->container);

        $uri = new Uri('http', 'localhost', null, '/some/dir/foo/bar');
        $request = new Request('GET', $uri, new Headers, [], [], new Stream(fopen('php://memory', 'w+')));

        $handler = $this->createMock(App::class);
        $handler->expects($this->once())->method('handle')->with(
            $this->callback(function (ServerRequestInterface $request): bool {
                return $request->getUri()->getPath() == '/foo/bar';
            })
        );

        $middleware($request, $handler);
    }
}
