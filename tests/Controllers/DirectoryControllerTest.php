<?php

namespace Tests\Controllers;

use App\Controllers\DirectoryController;
use App\Providers\TwigProvider;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;
use Tests\TestCase;

class DirectoryControllerTest extends TestCase
{
    /** @dataProvider configOptions */
    public function test_it_returns_a_successful_response(
        bool $hideAppFiles,
        bool $hideVcsFiles,
        bool $displayReadmes
    ): void {
        $this->config->set('app.hide_app_files', $hideAppFiles);
        $this->config->set('app.hide_vcs_files', $hideVcsFiles);
        $this->config->set('app.display_readmes', $displayReadmes);

        $this->container->call(TwigProvider::class);

        $controller = new DirectoryController(
            $this->container,
            $this->config,
            $this->container->get(Twig::class)
        );

        chdir($this->filePath('.'));
        $response = $controller(
            new Finder(),
            $this->createMock(Request::class),
            new Response()
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @dataProvider configOptions */
    public function test_it_returns_a_successful_response_when_listing_a_subdirectory(
        bool $hideAppFiles,
        bool $hideVcsFiles,
        bool $displayReadmes
    ): void {
        $this->config->set('app.hide_app_files', $hideAppFiles);
        $this->config->set('app.hide_vcs_files', $hideVcsFiles);
        $this->config->set('app.display_readmes', $displayReadmes);

        $this->container->call(TwigProvider::class);

        $controller = new DirectoryController(
            $this->container,
            $this->config,
            $this->container->get(Twig::class)
        );

        chdir($this->filePath('.'));
        $response = $controller(
            new Finder(),
            $this->createMock(Request::class),
            new Response(),
            'subdir'
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_it_returns_a_404_error_when_not_found(): void
    {
        $this->container->call(TwigProvider::class);

        $controller = new DirectoryController(
            $this->container,
            $this->config,
            $this->container->get(Twig::class)
        );

        chdir($this->filePath('.'));
        $response = $controller(
            new Finder(),
            $this->createMock(Request::class),
            new Response(),
            '404'
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_it_returns_a_successful_response_for_a_search_request(): void
    {
        $this->container->call(TwigProvider::class);

        $controller = new DirectoryController(
            $this->container,
            $this->config,
            $this->container->get(Twig::class)
        );

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn([
            'search' => 'charlie'
        ]);

        chdir($this->filePath('.'));
        $response = $controller(
            new Finder(),
            $request,
            new Response()
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Provide config options in the following order:
     * [ app.hide_app_files, app.hide_vcs_files, app.display_readmes ].
     *
     * @return array
     */
    public function configOptions(): array
    {
        return [
            [true, false, false],
            [true, true, false],
            [true, false, true],
            [true, true, true],
            [false, true, false],
            [false, true, true],
            [false, false, true],
            [false, false, false],
        ];
    }
}
