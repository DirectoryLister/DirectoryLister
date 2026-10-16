<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers;
use App\Controllers\IndexController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Tests\TestCase;

#[CoversClass(IndexController::class)]
class IndexControllerTest extends TestCase
{
    private Request&MockObject $request;

    private Response $response;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = $this->createMock(Request::class);
        $this->response = new Response;
    }

    #[Test]
    public function it_handles_a_file_request(): void
    {
        $this->request->method('getQueryParams')->willReturn(['file' => 'file.test']);
        $this->mock(Controllers\FileController::class)->method('__invoke')->willReturn($this->response);

        $controller = $this->makeController();

        $response = $controller($this->request, $this->response);

        $this->assertSame($this->response, $response);
    }

    #[Test]
    public function it_handles_a_file_info_request(): void
    {
        $this->request->method('getQueryParams')->willReturn(['info' => 'file.test']);
        $this->mock(Controllers\FileInfoController::class)->method('__invoke')->willReturn($this->response);

        $controller = $this->makeController();

        $response = $controller($this->request, $this->response);

        $this->assertSame($this->response, $response);
    }

    #[Test]
    public function it_handles_a_search_request(): void
    {
        $this->request->method('getQueryParams')->willReturn(['search' => 'file.test']);
        $this->mock(Controllers\SearchController::class)->method('__invoke')->willReturn($this->response);

        $controller = $this->makeController();

        $response = $controller($this->request, $this->response);

        $this->assertSame($this->response, $response);
    }

    #[Test]
    public function it_handles_a_zip_request(): void
    {
        $this->request->method('getQueryParams')->willReturn(['zip' => 'subdir']);
        $this->mock(Controllers\ZipController::class)->method('__invoke')->willReturn($this->response);

        $controller = $this->makeController();

        $response = $controller($this->request, $this->response);

        $this->assertSame($this->response, $response);
    }

    #[Test]
    public function it_handles_a_directory_request(): void
    {
        $this->request->method('getQueryParams')->willReturn(['dir' => 'some/directory']);
        $this->mock(Controllers\DirectoryController::class)->method('__invoke')->willReturn($this->response);

        $controller = $this->makeController();

        $response = $controller($this->request, $this->response);

        $this->assertSame($this->response, $response);
    }

    #[Test]
    public function it_handles_a_directory_request_by_default(): void
    {
        $this->request->method('getQueryParams')->willReturn([]);
        $this->mock(Controllers\DirectoryController::class)->method('__invoke')->willReturn($this->response);

        $controller = $this->makeController();

        $response = $controller($this->request, $this->response);

        $this->assertSame($this->response, $response);
    }

    #[Test]
    public function it_forwards_the_route_path_to_the_directory_controller(): void
    {
        $this->request->method('getQueryParams')->willReturn([]);
        $this->mock(Controllers\DirectoryController::class)->method('__invoke')->willReturn($this->response);

        $controller = $this->makeController();

        $response = $controller($this->request, $this->response, 'some/directory');

        $this->assertSame($this->response, $response);
    }

    #[Test]
    public function it_blocks_query_string_access_to_a_direct_link_from_outside(): void
    {
        $this->container->set('direct_links', 'direct_links/**');
        $this->request->method('getQueryParams')->willReturn(['dir' => 'direct_links']);

        $controller = $this->makeController();

        $response = $controller($this->request, $this->response);

        $this->assertEquals(404, $response->getStatusCode());
    }

    #[Test]
    public function it_allows_query_string_access_to_a_direct_link_from_within(): void
    {
        $this->container->set('direct_links', 'direct_links/**');
        $this->request->method('getQueryParams')->willReturn(['dir' => 'direct_links']);
        $this->mock(Controllers\DirectoryController::class)->method('__invoke')->willReturn($this->response);

        $controller = $this->makeController();

        $response = $controller($this->request, $this->response, 'direct_links');

        $this->assertSame($this->response, $response);
    }

    #[Test]
    public function it_allows_query_string_access_to_a_direct_linked_file_from_within(): void
    {
        $this->container->set('direct_links', 'direct_links/**');
        $this->request->method('getQueryParams')->willReturn(['info' => 'direct_links/index.htm']);
        $this->mock(Controllers\FileInfoController::class)->method('__invoke')->willReturn($this->response);

        $controller = $this->makeController();

        $response = $controller($this->request, $this->response, 'direct_links');

        $this->assertSame($this->response, $response);
    }

    #[Test]
    public function it_allows_query_string_access_to_a_non_direct_link(): void
    {
        $this->container->set('direct_links', 'direct_links/**');
        $this->request->method('getQueryParams')->willReturn(['dir' => 'subdir']);
        $this->mock(Controllers\DirectoryController::class)->method('__invoke')->willReturn($this->response);

        $controller = $this->makeController();

        $response = $controller($this->request, $this->response);

        $this->assertSame($this->response, $response);
    }

    private function makeController(): IndexController
    {
        return new IndexController(
            $this->container,
            $this->container->get(\Slim\Views\Twig::class),
            $this->container->get(\Symfony\Contracts\Translation\TranslatorInterface::class),
            $this->container->get(\App\Actions\IsDirectLink::class),
        );
    }
}
