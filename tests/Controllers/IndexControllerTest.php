<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers;
use App\Controllers\IndexController;
use DI\Container;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Tests\TestCase;

#[CoversClass(IndexController::class)]
class IndexControllerTest extends TestCase
{
    /** @var Container&MockObject */
    protected $container;
    /** @var Request&MockObject */
    private $request;

    /** @var Response&MockObject */
    private $response;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = $this->createMock(Request::class);
        $this->response = $this->createMock(Response::class);
        $this->container = $this->createMock(Container::class);
    }

    #[Test]
    public function it_handles_a_file_request(): void
    {
        $this->request->method('getQueryParams')->willReturn(['file' => 'file.test']);

        $this->container->expects($this->once())->method('call')->with(
            Controllers\FileController::class,
            [$this->request, $this->response]
        )->willReturn($this->response);

        $controller = new Controllers\IndexController($this->container);

        $response = $controller($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    #[Test]
    public function it_handles_a_file_info_request(): void
    {
        $this->request->method('getQueryParams')->willReturn(['info' => 'file.test']);

        $this->container->expects($this->once())->method('call')->with(
            Controllers\FileInfoController::class,
            [$this->request, $this->response]
        )->willReturn($this->response);

        $controller = new Controllers\IndexController($this->container);

        $response = $controller($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    #[Test]
    public function it_handles_a_search_request(): void
    {
        $this->request->method('getQueryParams')->willReturn(['search' => 'file.test']);

        $this->container->expects($this->once())->method('call')->with(
            Controllers\SearchController::class,
            [$this->request, $this->response]
        )->willReturn($this->response);

        $controller = new Controllers\IndexController($this->container);

        $response = $controller($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    #[Test]
    public function it_handles_a_zip_request(): void
    {
        $this->request->method('getQueryParams')->willReturn(['zip' => 'subdir']);

        $this->container->expects($this->once())->method('call')->with(
            Controllers\ZipController::class,
            [$this->request, $this->response]
        )->willReturn($this->response);

        $controller = new Controllers\IndexController($this->container);

        $response = $controller($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    #[Test]
    public function it_handles_a_directory_request(): void
    {
        $this->request->method('getQueryParams')->willReturn(['dir' => 'some/directory']);

        $this->container->expects($this->once())->method('call')->with(
            Controllers\DirectoryController::class,
            [$this->request, $this->response]
        )->willReturn($this->response);

        $controller = new Controllers\IndexController($this->container);

        $response = $controller($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    #[Test]
    public function it_handles_a_directory_request_by_default(): void
    {
        $this->container->expects($this->once())->method('call')->with(
            Controllers\DirectoryController::class,
            [$this->request, $this->response]
        )->willReturn($this->response);

        $controller = new Controllers\IndexController($this->container);

        $response = $controller($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
