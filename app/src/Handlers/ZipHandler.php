<?php

namespace App\Handlers;

use App\Support\Str;
use App\TemporaryFile;
use DI\Container;
use PHLAK\Config\Interfaces\ConfigInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Contracts\Translation\TranslatorInterface;
use ZipArchive;

class ZipHandler
{
    /** @var Container The application container */
    protected $container;

    /** @var ConfigInterface The application config */
    protected $config;

    /** @var Finder The Finder Component */
    protected $finder;

    /** @var TranslatorInterface Translator component */
    protected $translator;

    /**
     * Create a new ZipHandler object.
     *
     * @param \DI\Container                                      $container
     * @param \PHLAK\Config\Interfaces\ConfigInterface           $config
     * @param \PhpCsFixer\Finder                                 $finder
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(
        Container $container,
        ConfigInterface $config,
        Finder $finder,
        TranslatorInterface $translator
    ) {
        $this->container = $container;
        $this->config = $config;
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

        if (! $this->config->get('app.zip_downloads', true) || ! realpath($path)) {
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
            $this->container->get('base_path') . '/app/cache'
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
