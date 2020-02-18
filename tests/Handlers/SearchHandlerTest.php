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

        $handler = new SearchHandler(new Finder, $this->container->get(Twig::class));

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['search' => 'charlie']);

        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_it_returns_a_successful_response_for_a_blank_search(): void
    {
        $this->container->call(TwigProvider::class);

        $handler = new SearchHandler(new Finder, $this->container->get(Twig::class));

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['search' => '']);

        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
