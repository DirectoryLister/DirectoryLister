<?php

namespace App\Controllers;

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
use ZipArchive;

class ZipController
{
    /** @var Config The application configuration */
    protected $config;

    /** @var CacheInterface The application cache */
    protected $cache;

    /** @var Finder The Finder Component */
    protected $finder;

    /** @var TranslatorInterface Translator component */
    protected $translator;

    /** Create a new ZipHandler object. */
    public function __construct(
        Config $config,
        CacheInterface $cache,
        Finder $finder,
        TranslatorInterface $translator
    ) {
        $this->config = $config;
        $this->cache = $cache;
        $this->finder = $finder;
        $this->translator = $translator;
    }

    /** Invoke the ZipHandler. */
    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $path = $request->getQueryParams()['zip'];

        if (! $this->config->get('zip_downloads') || ! is_dir($path)) {
            return $response->withStatus(404, $this->translator->trans('error.file_not_found'));
        }

        $response->getBody()->write(
            $this->cache->get(sprintf('zip-%s', sha1($path)), function () use ($path): string {
                return $this->createZip($path)->getContents();
            })
        );

        return $response->withHeader('Content-Type', 'application/zip')
            ->withHeader('Content-Disposition', sprintf(
                'attachment; filename="%s.zip"',
                $this->generateFileName($path)
            ));
    }

    /** Create a zip file from a directory. */
    protected function createZip(string $path): TemporaryFile
    {
        $zip = new ZipArchive;
        $zip->open((string) $tempFile = new TemporaryFile(
            $this->config->get('cache_path')
        ), ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($this->finder->in($path)->files() as $file) {
            $zip->addFile($file->getRealPath(), $this->stripPath($file, $path));
        }

        $zip->close();

        return $tempFile;
    }

    /** Return the path to a file with the preceding root path stripped. */
    protected function stripPath(SplFileInfo $file, string $path): string
    {
        $pattern = sprintf(
            '/^%s%s?/', preg_quote($path, '/'), preg_quote(DIRECTORY_SEPARATOR, '/')
        );

        return preg_replace($pattern, '', $file->getPathname());
    }

    /** Generate the file name for a path. */
    protected function generateFileName(string $path): string
    {
        $filename = Str::explode($path, DIRECTORY_SEPARATOR)->last();

        return $filename == '.' ? 'Home' : $filename;
    }
}
