<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\FileController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Tests\TestCase;

#[CoversClass(FileController::class)]
class FileControllerTest extends TestCase
{
    #[Test]
    public function it_returns_a_successful_response_for_a_file_request(): void
    {
        $controller = $this->container->get(FileController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['file' => 'README.md']);

        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(200, $response->getStatusCode());

        $this->assertSame([
            'Content-Disposition' => ['attachment; filename="README.md"'],
            'Content-Type' => ['text/plain'],
            'Content-Length' => ['30'],
        ], $response->getHeaders());

        $this->assertSame("Test README.md; please ignore\n", (string) $response->getBody());
    }

    #[Test]
    public function it_returns_a_404_error_when_not_found(): void
    {
        $controller = $this->container->get(FileController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['file' => '404']);

        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(404, $response->getStatusCode());
    }
}
