<?php

namespace Tests\Controllers;

use App\Controllers\DirectoryController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;
use Symfony\Contracts\Translation\TranslatorInterface;
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
    public function it_returns_a_404_error_when_not_found(): void
    {
        $controller = new DirectoryController(
            $this->container,
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
}
