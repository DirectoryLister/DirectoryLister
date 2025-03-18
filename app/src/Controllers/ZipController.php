<?php

declare(strict_types=1);

namespace App\Controllers;

use App\CallbackStream;
use App\Config;
use App\Support\Str;
use DateTime;
use DI\Container;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Contracts\Translation\TranslatorInterface;
use ZipStream\CompressionMethod;
use ZipStream\Exception as ZipStreamException;
use ZipStream\OperationMode;
use ZipStream\ZipStream;

class ZipController
{
    public function __construct(
        private Container $container,
        private Config $config,
        private Finder $finder,
        private TranslatorInterface $translator
    ) {}

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $path = $this->container->call('full_path', ['path' => $request->getQueryParams()['zip']]);

        if (! $this->config->get('zip_downloads') || ! is_dir($path)) {
            return $response->withStatus(404, $this->translator->trans('error.file_not_found'));
        }

        $response = $response
            ->withHeader('Content-Type', 'application/zip')
            ->withHeader('Content-Disposition', sprintf(
                'attachment; filename="%s.zip"',
                $this->generateFileName($path)
            ))
            ->withHeader('X-Accel-Buffering', 'no');

        $files = $this->finder->in($path)->files();

        try {
            $zip = $this->createZip($path, $files);
        } catch (ZipStreamException) {
            return $response->withStatus(500, $this->translator->trans('error.unexpected'));
        }

        $size = $zip->finish();

        return $response->withHeader('Content-Length', (string) $size)->withBody(
            new CallbackStream(static function () use ($zip): void {
                $zip->executeSimulation();
            })
        );
    }

    /**
     * Create a zip stream from a directory.
     *
     * @throws \ZipStream\Exception
     */
    private function createZip(string $path, Finder $files): ZipStream
    {
        $compressionMethod = $this->config->get('zip_compress') ? CompressionMethod::DEFLATE : CompressionMethod::STORE;

        $zip = new ZipStream(
            sendHttpHeaders: false,
            operationMode: OperationMode::SIMULATE_LAX
        );

        foreach ($files as $file) {
            try {
                /** @throws RuntimeException */
                $modifiedTime = new DateTime('@' . (int) $file->getMTime());
            } catch (RuntimeException) {
                $modifiedTime = new DateTime('@' . (int) lstat($file->getPathname())['mtime']);
            }

            $zip->addFileFromPath(
                $this->stripPath($file, $path),
                (string) $file->getRealPath(),
                compressionMethod: $compressionMethod,
                lastModificationDateTime: $modifiedTime,
                exactSize: $file->getSize()
            );
        }

        return $zip;
    }

    /** Return the path to a file with the preceding root path stripped. */
    private function stripPath(SplFileInfo $file, string $path): string
    {
        $pattern = sprintf('/^%s%s?/', preg_quote($path, '/'), preg_quote(DIRECTORY_SEPARATOR, '/'));

        return (string) preg_replace($pattern, '', $file->getPathname());
    }

    /** Generate the file name for a path. */
    private function generateFileName(string $path): string
    {
        $filename = (string) Str::explode($path, DIRECTORY_SEPARATOR)->last();

        return $filename == '.' ? 'Home' : $filename;
    }
}
