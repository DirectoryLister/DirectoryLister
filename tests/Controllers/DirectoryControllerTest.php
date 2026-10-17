<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\DirectoryController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Tests\TestCase;

#[CoversClass(DirectoryController::class)]
class DirectoryControllerTest extends TestCase
{
    /**
     * Provide config options in the following order:
     * [ app.hide_app_files, app.hide_vcs_files, app.display_readmes ].
     */
    public static function configOptions(): array
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

    #[Test, DataProvider('configOptions')]
    public function it_returns_a_successful_response(
        bool $hideAppFiles,
        bool $hideVcsFiles,
        bool $displayReadmes
    ): void {
        $this->container->set('hide_app_files', $hideAppFiles);
        $this->container->set('hide_vcs_files', $hideVcsFiles);
        $this->container->set('display_readmes', $displayReadmes);

        $controller = $this->container->get(DirectoryController::class);

        chdir($this->filePath('.'));
        $response = $controller($this->createMock(Request::class), new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    #[Test, DataProvider('configOptions')]
    public function it_returns_a_successful_response_when_listing_a_subdirectory(
        bool $hideAppFiles,
        bool $hideVcsFiles,
        bool $displayReadmes
    ): void {
        $this->container->set('hide_app_files', $hideAppFiles);
        $this->container->set('hide_vcs_files', $hideVcsFiles);
        $this->container->set('display_readmes', $displayReadmes);

        $controller = $this->container->get(DirectoryController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['dir' => 'subdir']);

        chdir($this->filePath('.'));
        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    #[Test]
    public function it_returns_a_successful_response_when_listing_a_path_based_directory(): void
    {
        $controller = $this->container->get(DirectoryController::class);

        $request = $this->createMock(Request::class);

        chdir($this->filePath('.'));
        $response = $controller($request, new Response, 'subdir');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    #[Test]
    public function it_returns_a_404_error_when_not_found(): void
    {
        $controller = $this->container->get(DirectoryController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['dir' => '404']);

        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    #[Test]
    public function it_renders_a_hidden_readme_but_does_not_list_it(): void
    {
        $this->container->set('display_readmes', true);
        $this->container->set('hidden_files', ['README.md']);

        $controller = $this->container->get(DirectoryController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['dir' => '.']);

        chdir($this->filePath('.'));
        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $body = (string) $response->getBody();

        $this->assertStringContainsString(trim((string) file_get_contents($this->filePath('README.md'))), $body);
        $this->assertStringNotContainsString('href="?file=README.md"', $body);
        $this->assertStringContainsString('href="?file=README.txt"', $body);
    }

    #[Test]
    public function it_returns_a_404_error_when_the_folder_is_hidden(): void
    {
        $this->container->set('hidden_files', ['subdir']);

        $controller = $this->container->get(DirectoryController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['dir' => 'subdir']);

        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    #[Test]
    public function it_returns_a_404_error_when_a_hidden_folder_is_requested_via_a_dot_segment(): void
    {
        $this->container->set('hidden_files', ['subdir']);

        $controller = $this->container->get(DirectoryController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['dir' => './subdir']);

        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
