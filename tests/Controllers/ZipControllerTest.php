<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\ZipController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Tests\TestCase;

#[CoversClass(ZipController::class)]
class ZipControllerTest extends TestCase
{
    public static function environmentBooleans(): array
    {
        return [
            'false' => ['false', false],
            'true' => ['true', true],
        ];
    }

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

    #[Test, DataProvider('environmentBooleans')]
    public function it_respects_the_zip_downloads_environment_variable(string $value, bool $enabled): void
    {
        $previous = $_ENV;
        $_ENV['ZIP_DOWNLOADS'] = $value;

        try {
            $controller = $this->container->get(ZipController::class);
            $request = $this->createMock(Request::class);
            $request->method('getQueryParams')->willReturn(['zip' => 'subdir']);

            $response = $controller($request, new Response);

            $this->assertSame($enabled ? 200 : 404, $response->getStatusCode());
        } finally {
            $_ENV = $previous;
        }
    }

    #[Test, DataProvider('environmentBooleans')]
    public function it_respects_the_zip_compress_environment_variable(string $value, bool $compressed): void
    {
        $previous = $_ENV;
        $_ENV['ZIP_COMPRESS'] = $value;

        try {
            $controller = $this->container->get(ZipController::class);
            $request = $this->createMock(Request::class);
            $request->method('getQueryParams')->willReturn(['zip' => 'subdir']);

            $response = $controller($request, new Response);

            ob_start();
            $response->getBody()->read(1);
            $zip = (string) ob_get_clean();

            $this->assertSame(200, $response->getStatusCode());
            $this->assertSame('PK' . chr(3) . chr(4), substr($zip, 0, 4));
            $this->assertSame($compressed ? 8 : 0, ord($zip[8]) | (ord($zip[9]) << 8));
        } finally {
            $_ENV = $previous;
        }
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
}
