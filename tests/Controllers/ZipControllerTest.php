<?php

namespace Tests\Controllers;

use App\Controllers\ZipController;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Finder\Finder;
use Symfony\Contracts\Translation\TranslatorInterface;
use Tests\TestCase;

/** @covers \App\Controllers\ZipController */
class ZipControllerTest extends TestCase
{
    public function test_it_returns_a_successful_response_for_a_zip_request(): void
    {
        $controller = new ZipController(
            $this->config,
            new Finder,
            $this->container->get(TranslatorInterface::class)
        );

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['zip' => 'subdir']);

        chdir($this->filePath('.'));
        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/zip', $response->getHeader('Content-Type')[0]);
    }

    public function test_it_returns_a_404_error_when_not_found(): void
    {
        $controller = new ZipController(
            $this->config,
            new Finder,
            $this->container->get(TranslatorInterface::class)
        );

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['zip' => '404']);

        chdir($this->filePath('.'));
        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_it_returns_a_404_error_when_disabled_via_config(): void
    {
        $this->container->set('zip_downloads', false);
        $controller = new ZipController(
            $this->config,
            new Finder,
            $this->container->get(TranslatorInterface::class)
        );

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['zip' => 'subdir']);

        chdir($this->filePath('.'));
        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
