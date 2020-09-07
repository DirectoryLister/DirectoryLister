<?php

namespace Tests\Controllers;

use App\Controllers\DirectoryController;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;
use Symfony\Contracts\Translation\TranslatorInterface;
use Tests\TestCase;

/** @covers \App\Controllers\DirectoryController */
class DirectoryControllerTest extends TestCase
{
    /** @dataProvider configOptions */
    public function test_it_returns_a_successful_response(
        bool $hideAppFiles,
        bool $hideVcsFiles,
        bool $displayReadmes
    ): void {
        $this->container->set('hide_app_files', $hideAppFiles);
        $this->container->set('hide_vcs_files', $hideVcsFiles);
        $this->container->set('display_readmes', $displayReadmes);

        $controller = new DirectoryController(
            $this->config,
            new Finder,
            $this->container->get(Twig::class),
            $this->container->get(TranslatorInterface::class)
        );

        chdir($this->filePath('.'));
        $response = $controller($this->createMock(Request::class), new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @dataProvider configOptions */
    public function test_it_returns_a_successful_response_when_listing_a_subdirectory(
        bool $hideAppFiles,
        bool $hideVcsFiles,
        bool $displayReadmes
    ): void {
        $this->container->set('hide_app_files', $hideAppFiles);
        $this->container->set('hide_vcs_files', $hideVcsFiles);
        $this->container->set('display_readmes', $displayReadmes);

        $controller = new DirectoryController(
            $this->config,
            new Finder,
            $this->container->get(Twig::class),
            $this->container->get(TranslatorInterface::class)
        );

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['dir' => 'subdir']);

        chdir($this->filePath('.'));
        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_it_returns_a_404_error_when_not_found(): void
    {
        $controller = new DirectoryController(
            $this->config,
            new Finder,
            $this->container->get(Twig::class),
            $this->container->get(TranslatorInterface::class)
        );

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['dir' => '404']);

        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Provide config options in the following order:
     * [ app.hide_app_files, app.hide_vcs_files, app.display_readmes ].
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
