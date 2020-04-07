<?php

namespace App\Controllers;

use App\Support\Str;
use App\TemporaryFile;
use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Contracts\Translation\TranslatorInterface;
use ZipArchive;

class ZipController
{
    /** @var Container The application container */
    protected $container;

    /** @var Finder The Finder Component */
    protected $finder;

    /** @var TranslatorInterface Translator component */
    protected $translator;

    /**
     * Create a new ZipHandler object.
     *
     * @param \DI\Container                                      $container
     * @param \PhpCsFixer\Finder                                 $finder
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(
        Container $container,
        Finder $finder,
        TranslatorInterface $translator
    ) {
        $this->container = $container;
        $this->finder = $finder;
        $this->translator = $translator;
    }

    /**
     * Invoke the ZipHandler.
     *
     * @param \Slim\Psr7\Request  $request
     * @param \Slim\Psr7\Response $response
     *
     *  @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $path = $request->getQueryParams()['zip'];

        if (! $this->container->get('zip_downloads') || ! realpath($path)) {
            return $response->withStatus(404, $this->translator->trans('error.file_not_found'));
        }

        $response->getBody()->write($this->createZip($path)->getContents());

        return $response->withHeader('Content-Type', 'application/zip')
            ->withHeader('Content-Disposition', sprintf(
                'attachment; filename="%s.zip"',
                $this->generateFileName($path)
            ));
    }

    /**
     * Create a zip file from a directory.
     *
     * @param string $path
     *
     * @return \App\TemporaryFile
     */
    protected function createZip(string $path): TemporaryFile
    {
        $zip = new ZipArchive;
        $zip->open((string) $tempFile = new TemporaryFile(
            $this->container->get('cache_path')
        ), ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($this->finder->in($path)->files() as $file) {
            $zip->addFile($file->getRealPath(), $this->stripPath($file, $path));
        }

        $zip->close();

        return $tempFile;
    }

    /**
     * Return the path to a file with the preceding root path stripped.
     *
     * @param \Symfony\Component\Finder\SplFileInfo $file
     * @param string                                $path
     *
     * @return string
     */
    protected function stripPath(SplFileInfo $file, string $path): string
    {
        $pattern = sprintf(
            '/^%s%s?/', preg_quote($path, '/'), preg_quote(DIRECTORY_SEPARATOR, '/')
        );

        return preg_replace($pattern, '', $file->getPathname());
    }

    /**
     * Generate the file name for a path.
     *
     * @param string $path
     *
     * @return string
     */
    protected function generateFileName(string $path): string
    {
        $filename = Str::explode($path, DIRECTORY_SEPARATOR)->last();

        return $filename == '.' ? 'Home' : $filename;
    }
}
