<?php

namespace Tests\Controllers;

use App\Controllers\FileInfoController;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Tests\TestCase;

class FileInfoControllerTest extends TestCase
{
    public function test_it_can_return_a_successful_response(): void
    {
        $controller = new FileInfoController($this->container, $this->config);

        $response = $controller(new Response(), 'README.md');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_it_can_return_a_not_found_response(): void
    {
        $controller = new FileInfoController($this->container, $this->config);

        $response = $controller(new Response(), 'not_a_file.test');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_it_returns_an_error_when_file_size_is_too_large(): void
    {
        $this->config->set('app.max_hash_size', 10);
        $controller = new FileInfoController($this->container, $this->config);

        $response = $controller(new Response(), 'README.md');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
    }
}
