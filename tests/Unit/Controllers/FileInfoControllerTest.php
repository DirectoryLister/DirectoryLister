<?php

namespace Tests\Unit\Controllers;

use App\Controllers\FileInfoController;
use PHLAK\Config\Config;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

class FileInfoControllerTest extends TestCase
{
    /** @var Config The application config */
    protected $config;

    public function setUp(): void
    {
        $this->config = new Config([
            'app' => [
                'max_hash_size' => 1000000000
            ],
        ]);
    }

    public function test_it_can_return_a_successful_response()
    {
        $controller = new FileInfoController($this->config);

        $response = $controller(new Response(), __DIR__ . '/../../files/alpha.scss');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_it_can_return_a_not_found_response()
    {
        $controller = new FileInfoController($this->config);

        $response = $controller(new Response(), 'not_a_file.test');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_it_returns_an_error_when_file_size_is_too_large()
    {
        $this->config->set('app.max_hash_size', 10);
        $controller = new FileInfoController($this->config);

        $response = $controller(new Response(), __DIR__ . '/../../files/alpha.scss');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
    }
}
