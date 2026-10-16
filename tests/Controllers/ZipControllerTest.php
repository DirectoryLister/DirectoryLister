<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\ZipController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Tests\TestCase;
use ZipArchive;

#[CoversClass(ZipController::class)]
class ZipControllerTest extends TestCase
{
    #[Test]
    public function it_returns_a_successful_response_for_a_zip_request(): void
    {
        $controller = $this->container->get(ZipController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['zip' => 'subdir']);

        chdir($this->filePath('.'));
        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/zip', $response->getHeader('Content-Type')[0]);
    }

    #[Test]
    public function it_returns_a_404_error_when_not_found(): void
    {
        $controller = $this->container->get(ZipController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['zip' => '404']);

        chdir($this->filePath('.'));
        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    #[Test]
    public function it_returns_a_404_error_when_disabled_via_config(): void
    {
        $this->container->set('zip_downloads', false);

        $controller = $this->container->get(ZipController::class);

        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['zip' => 'subdir']);

        chdir($this->filePath('.'));
        $response = $controller($request, new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    #[Test]
    public function it_downloads_a_compressed_archive_with_the_original_file_contents(): void
    {
        $this->container->set('zip_compress', true);
        $controller = $this->container->get(ZipController::class);
        $request = $this->createMock(Request::class);
        $request->method('getQueryParams')->willReturn(['zip' => 'subdir']);

        $response = $controller($request, new Response);

        ob_start();
        $response->getBody()->read(1);
        $contents = (string) ob_get_clean();

        $path = tempnam(sys_get_temp_dir(), 'directory-lister-zip-');
        $this->assertIsString($path);
        file_put_contents($path, $contents);

        $archive = new ZipArchive;
        $this->assertTrue($archive->open($path));

        try {
            $this->assertSame(200, $response->getStatusCode());
            $this->assertSame(strlen($contents), (int) $response->getHeaderLine('Content-Length'));
            $this->assertSame(
                file_get_contents($this->filePath('subdir/alpha.scss')),
                $archive->getFromName('alpha.scss')
            );
        } finally {
            $archive->close();
            unlink($path);
        }
    }
}
