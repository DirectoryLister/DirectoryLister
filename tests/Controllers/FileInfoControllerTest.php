<?php

namespace Tests\Controllers;

use App\Controllers\FileInfoController;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Tests\TestCase;

/** @covers \App\Controllers\FileInfoController */
class FileInfoControllerTest extends TestCase
{
    public function test_it_can_return_a_successful_response(): void
    {
        $handler = new FileInfoController(
            $this->config,
            $this->cache,
            $this->container->get(TranslatorInterface::class)
        );

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

    public function test_it_can_return_a_not_found_response(): void
    {
        $handler = new FileInfoController(
            $this->config,
            $this->cache,
            $this->container->get(TranslatorInterface::class)
        );

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['info' => 'not_a_file.test']);

        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_it_returns_an_error_when_file_size_is_too_large(): void
    {
        $this->container->set('max_hash_size', 10);
        $handler = new FileInfoController(
            $this->config,
            $this->cache,
            $this->container->get(TranslatorInterface::class)
        );

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['info' => 'README.md']);

        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
    }
}
