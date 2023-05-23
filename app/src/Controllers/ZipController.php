<?php

namespace App\Controllers;

use App\CallbackStream;
use App\Config;
use App\Support\Str;
use App\TemporaryFile;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use ZipStream\Option\Archive;
use ZipStream\Option\File;
use ZipStream\Option\Method;
use ZipStream\ZipStream;

class ZipController
{
    /** Create a new ZipHandler object. */
    public function __construct(
        private Config $config,
        private CacheInterface $cache,
        private Finder $finder,
        private TranslatorInterface $translator
    ) {}

    /** Invoke the ZipHandler. */
    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $path = $request->getQueryParams()['zip'];

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
        
        $response = $this->augmentHeadersWithEstimatedSize($response, $path, $files);

        return $response->withBody(new CallbackStream(function () use ($path, $files) {
            $this->createZip($path, $files);
        }));
    }

    /** Create a zip stream from a directory. */
    protected function createZip(string $path, Finder $files): void
    {
        $compressionMethod = $this->config->get('zip_compress') ? Method::DEFLATE() : Method::STORE();

        $zipStreamOptions = new Archive();
        $zipStreamOptions->setLargeFileMethod($compressionMethod);
        $zipStreamOptions->setFlushOutput(true);

        $zip = new ZipStream(null, $zipStreamOptions);

        $fileOption = new File();
        $fileOption->setMethod($compressionMethod);

        foreach ($files as $file) {
            $zip->addFileFromPath($this->stripPath($file, $path), (string) $file->getRealPath(), $fileOption);
        }

        $zip->finish();
    }

    protected function augmentHeadersWithEstimatedSize(Response $response, string $path, Finder $files): Response
    {
        $estimate = 22;
        if (!$this->config->get('zip_compress')) {
            foreach ($files as $file) {
                $estimate += 76 + 2 * strlen($this->stripPath($file, $path)) + $file->getSize();
            }
            $response = $response->withHeader('Content-Length', (string) $estimate);
        }
        return $response;
    }

    /** Return the path to a file with the preceding root path stripped. */
    protected function stripPath(SplFileInfo $file, string $path): string
    {
        $pattern = sprintf(
            '/^%s%s?/', preg_quote($path, '/'), preg_quote(DIRECTORY_SEPARATOR, '/')
        );

        return (string) preg_replace($pattern, '', $file->getPathname());
    }

    /** Generate the file name for a path. */
    protected function generateFileName(string $path): string
    {
        $filename = Str::explode($path, DIRECTORY_SEPARATOR)->last();

        return $filename == '.' ? 'Home' : $filename;
    }
}
