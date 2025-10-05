<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\FileInfoController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Tests\TestCase;

#[CoversClass(FileInfoController::class)]
class FileInfoControllerTest extends TestCase
{
    #[Test]
    public function it_can_return_a_successful_response(): void
    {
        $handler = $this->container->get(FileInfoController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['info' => 'README.md']);

        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode([
            'hashes' => [
                'md5' => '6e35c5c3bca40dfb96cbb449fd06df38',
                'sha1' => '7ea619032a992824fac30026d3df919939c7ebfb',
                'sha256' => '40adf7348820699ed3e72dc950ccd8d8d538065a91eba3c76263c44b1d12df9c',
            ],
        ]), (string) $response->getBody());
    }

    #[Test]
    public function it_return_a_not_found_response_when_the_file_does_not_exist(): void
    {
        $handler = $this->container->get(FileInfoController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['info' => 'not_a_file.test']);

        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    #[Test]
    public function it_return_a_not_found_response_when_the_file_is_hidden(): void
    {
        $this->container->set('hidden_files', ['README.md']);

        $handler = $this->container->get(FileInfoController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['info' => 'README.md']);

        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    #[Test]
    public function it_returns_an_error_when_file_size_is_too_large(): void
    {
        $this->container->set('max_hash_size', 10);

        $handler = $this->container->get(FileInfoController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['info' => 'README.md']);

        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
    }
}
