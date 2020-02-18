<?php

namespace Tests\Handlers;

use App\Handlers\ZipHandler;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Finder\Finder;
use Tests\TestCase;

class ZipHandlerTest extends TestCase
{
    public function test_it_returns_a_successful_response_for_a_zip_request(): void
    {
        $handler = new ZipHandler($this->container, $this->config, new Finder);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['zip' => 'subdir']);

        chdir($this->filePath('.'));
        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/zip', finfo_buffer(
            finfo_open(), (string) $response->getBody(), FILEINFO_MIME_TYPE
        ));
    }

    public function test_it_returns_a_404_error_when_not_found(): void
    {
        $handler = new ZipHandler($this->container, $this->config, new Finder);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['zip' => '404']);

        chdir($this->filePath('.'));
        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_it_returns_a_404_error_when_disabled_via_config(): void
    {
        $this->config->set('app.zip_downloads', false);
        $handler = new ZipHandler($this->container, $this->config, new Finder);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['zip' => 'subdir']);

        chdir($this->filePath('.'));
        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
