<?php

namespace Tests\Unit\Controllers;

use App\Controllers\FileInfoController;
use PHLAK\Config\Config;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

class FileInfoControllerTest extends TestCase
{
    public function test_it_returns_a_response()
    {
        $controller = new FileInfoController(
            $this->createMock(Config::class)
        );

        $response = $controller(new Response(), 'tests/files');

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
