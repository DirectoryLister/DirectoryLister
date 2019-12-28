<?php

namespace Tests\Controllers;

use App\Bootstrap\ViewComposer;
use App\Controllers\DirectoryController;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;
use Tests\TestCase;

class DirectoryControllerTest extends TestCase
{
    public function test_it_returns_a_response(): void
    {
        $this->container->call(ViewComposer::class);

        $controller = new DirectoryController(
            $this->container,
            $this->config,
            $this->container->get(Twig::class)
        );

        $response = $controller(
            new Finder(),
            new Response(),
            'tests/files'
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
