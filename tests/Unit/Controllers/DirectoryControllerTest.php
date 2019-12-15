<?php

namespace Tests\Controllers;

use App\Controllers\DirectoryController;
use PHLAK\Config\Config;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;

class DirectoryControllerTest extends TestCase
{
    public function test_it_returns_a_response()
    {
        $controller = new DirectoryController(
            $this->createMock(Config::class),
            $this->createMock(Twig::class)
        );

        $response = $controller(
            $this->createMock(Finder::class),
            $this->createMock(Response::class),
            'tests/files'
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
