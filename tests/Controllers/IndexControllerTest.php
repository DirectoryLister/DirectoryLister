<?php

namespace Tests\Controllers;

use App\Controllers;
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
            Controllers\FileInfoController::class,
            [$request, $response = new Response]
        );

        $controller = new Controllers\IndexController($container);

        $controller($request, $response);
    }

    public function test_it_handles_a_search_request(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['search' => 'file.test']);

        $container = $this->createMock(Container::class);
        $container->expects($this->once())->method('call')->with(
            Controllers\SearchController::class,
            [$request, $response = new Response]
        );

        $controller = new Controllers\IndexController($container);

        $controller($request, $response);
    }

    public function test_it_handles_a_zip_request(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['zip' => 'subdir']);

        $container = $this->createMock(Container::class);
        $container->expects($this->once())->method('call')->with(
            Controllers\ZipController::class,
            [$request, $response = new Response]
        );

        $controller = new Controllers\IndexController($container);

        $controller($request, $response);
    }

    public function test_it_handles_a_directory_request(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['dir' => 'some/directory']);

        $container = $this->createMock(Container::class);
        $container->expects($this->once())->method('call')->with(
            Controllers\DirectoryController::class,
            [$request, $response = new Response]
        );

        $controller = new Controllers\IndexController($container);

        $controller($request, $response);
    }

    public function test_it_handles_a_directory_request_by_default(): void
    {
        $container = $this->createMock(Container::class);
        $container->expects($this->once())->method('call')->with(
            Controllers\DirectoryController::class,
            [$request = $this->createMock(Request::class), $response = new Response]
        );

        $controller = new Controllers\IndexController($container);

        $controller($request, $response);
    }
}
