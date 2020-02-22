<?php

namespace Tests\Bootstrap;

use App\Bootstrap\ErrorHandler;
use App\Providers\TwigProvider;
use Exception;
use Slim\Psr7\Request;
use Slim\Views\Twig;
use Tests\TestCase;

class ErrorHandlerTest extends TestCase
{
    public function test_it_returns_an_error(): void
    {
        $this->container->call(TwigProvider::class);
        $errorHandler = new ErrorHandler($this->container->get(Twig::class));

        $response = $errorHandler(
            $this->createMock(Request::class),
            new Exception('Test exception; please ignore'),
            true,
            true,
            true
        );

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('text/html', finfo_buffer(
            finfo_open(), (string) $response->getBody(), FILEINFO_MIME_TYPE
        ));
    }

    public function test_it_returns_an_error_for_a_json_request(): void
    {
        $this->container->call(TwigProvider::class);
        $errorHandler = new ErrorHandler($this->container->get(Twig::class));

        $request = $this->createMock(Request::class);
        $request->expects($this->once())->method('getHeaderLine')->willReturn(
            'application/json'
        );

        $response = $errorHandler(
            $request,
            new Exception('Test exception; please ignore'),
            true,
            true,
            true
        );

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('An unexpected error occured', json_decode(
            (string) $response->getBody()
        )->error->message);
    }
}
