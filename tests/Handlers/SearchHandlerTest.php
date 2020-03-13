<?php

namespace Tests\Handlers;

use App\Handlers\SearchHandler;
use App\Providers\TwigProvider;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;
use Tests\TestCase;

class SearchHandlerTest extends TestCase
{
    public function test_it_returns_a_successful_response_for_a_search_request(): void
    {
        $this->container->call(TwigProvider::class);

        $handler = new SearchHandler(new Finder, $this->container->get(Twig::class), $this->translator);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['search' => 'charlie']);

        chdir($this->filePath('.'));
        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotRegExp('/No results found/', (string) $response->getBody());
    }

    public function test_it_returns_no_results_found_when_there_are_no_results(): void
    {
        $this->container->call(TwigProvider::class);

        $handler = new SearchHandler(new Finder, $this->container->get(Twig::class), $this->translator);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['search' => 'test search; please ignore']);

        chdir($this->filePath('.'));
        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/No results found/', (string) $response->getBody());
    }

    public function test_it_returns_no_results_found_for_a_blank_search(): void
    {
        $this->container->call(TwigProvider::class);

        $handler = new SearchHandler(new Finder, $this->container->get(Twig::class), $this->translator);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['search' => '']);

        chdir($this->filePath('.'));
        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/No results found/', (string) $response->getBody());
    }
}
