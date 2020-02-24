<?php

namespace Tests\Exceptions;

use App\Exceptions\ErrorHandler;
use App\Providers\TwigProvider;
use Exception;
use Slim\Psr7\Request;
use Slim\Views\Twig;
use Tests\TestCase;

class ErrorHandlerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->container->call(TwigProvider::class);
    }

    public function test_it_returns_an_error(): void
    {
        $errorHandler = new ErrorHandler(
            $this->container->get(Twig::class),
            $this->translator
        );

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
        $errorHandler = new ErrorHandler(
            $this->container->get(Twig::class),
            $this->translator
        );

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
        $this->assertEquals('An unexpected error occurred', json_decode(
            (string) $response->getBody()
        )->error->message);
    }
}
