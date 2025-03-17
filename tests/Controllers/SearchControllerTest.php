<?php

namespace Tests\Controllers;

use App\Controllers\SearchController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Tests\TestCase;

#[CoversClass(SearchController::class)]
class SearchControllerTest extends TestCase
{
    #[Test]
    public function it_returns_a_successful_response_for_a_search_request(): void
    {
        $handler = $this->container->get(SearchController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['search' => 'charlie']);

        chdir($this->filePath('.'));
        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringNotContainsString('No results found', (string) $response->getBody());
    }

    #[Test]
    public function it_returns_no_results_found_when_there_are_no_results(): void
    {
        $handler = $this->container->get(SearchController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['search' => 'test search; please ignore']);

        chdir($this->filePath('.'));
        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('No results found', (string) $response->getBody());
    }

    #[Test]
    public function it_returns_no_results_found_for_a_blank_search(): void
    {
        $handler = $this->container->get(SearchController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['search' => '']);

        chdir($this->filePath('.'));
        $response = $handler($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('No results found', (string) $response->getBody());
    }
}
