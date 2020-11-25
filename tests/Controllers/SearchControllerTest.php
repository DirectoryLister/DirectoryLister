<?php

namespace Tests\Controllers;

use App\Controllers\SearchController;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;
use Symfony\Contracts\Translation\TranslatorInterface;
use Tests\TestCase;

/** @covers \App\Controllers\SearchController */
class SearchControllerTest extends TestCase
{
    public function test_it_returns_a_successful_response_for_a_search_request(): void
    {
        $handler = new SearchController(
            new Finder,
            $this->container->get(Twig::class),
            $this->container->get(TranslatorInterface::class)
        );

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['search' => 'charlie']);

        chdir($this->filePath('.'));
        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringNotContainsString('No results found', (string) $response->getBody());
    }

    public function test_it_returns_no_results_found_when_there_are_no_results(): void
    {
        $handler = new SearchController(
            new Finder,
            $this->container->get(Twig::class),
            $this->container->get(TranslatorInterface::class)
        );

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['search' => 'test search; please ignore']);

        chdir($this->filePath('.'));
        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('No results found', (string) $response->getBody());
    }

    public function test_it_returns_no_results_found_for_a_blank_search(): void
    {
        $handler = new SearchController(
            new Finder,
            $this->container->get(Twig::class),
            $this->container->get(TranslatorInterface::class)
        );

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['search' => '']);

        chdir($this->filePath('.'));
        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('No results found', (string) $response->getBody());
    }
}
