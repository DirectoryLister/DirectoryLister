<?php

namespace Tests\Controllers;

use App\Controllers\IndexController;
use App\Handlers;
use DI\Container;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    public function test_it_handles_a_file_info_request(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['info' => 'file.test']);

        $container = $this->createMock(Container::class);
        $container->expects($this->once())->method('call')->with(
            Handlers\FileInfoHandler::class,
            [$request, $response = new Response]
        );

        $controller = new IndexController($container);

        $controller($request, $response);
    }

    public function test_it_handles_a_search_request(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['search' => 'file.test']);

        $container = $this->createMock(Container::class);
        $container->expects($this->once())->method('call')->with(
            Handlers\SearchHandler::class,
            [$request, $response = new Response]
        );

        $controller = new IndexController($container);

        $controller($request, $response);
    }

    public function test_it_handles_a_directory_request(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['dir' => 'some/directory']);

        $container = $this->createMock(Container::class);
        $container->expects($this->once())->method('call')->with(
            Handlers\DirectoryHandler::class,
            [$request, $response = new Response]
        );

        $controller = new IndexController($container);

        $controller($request, $response);
    }

    public function test_it_handles_a_directory_request_by_default(): void
    {
        $container = $this->createMock(Container::class);
        $container->expects($this->once())->method('call')->with(
            Handlers\DirectoryHandler::class,
            [$request = $this->createMock(Request::class), $response = new Response]
        );

        $controller = new IndexController($container);

        $controller($request, $response);
    }
}
