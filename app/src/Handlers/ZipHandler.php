<?php

namespace App\Handlers;

use App\TemporaryFile;
use DI\Container;
use PHLAK\Config\Config;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Contracts\Translation\TranslatorInterface;
use Tightenco\Collect\Support\Collection;
use ZipArchive;

class ZipHandler
{
    /** @var Container The application container */
    protected $container;

    /** @var Config The application config */
    protected $config;

    /** @var Finder The Finder Component */
    protected $finder;

    /** @var TranslatorInterface Translator component */
    protected $translator;

    /**
     * Create a new ZipHandler object.
     *
     * @param \DI\Container      $container
     * @param \PhpCsFixer\Finder $finder
     */
    public function __construct(
        Container $container,
        Config $config,
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

        $zip = new ZipArchive;
        $zip->open((string) $tempFile = new TemporaryFile(
            $this->container->get('base_path') . '/app/cache'
        ), ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($this->finder->in($path)->files() as $file) {
            $zip->addFile($file->getRealPath(), $this->stripPath($file, $path));
        }

        $zip->close();

        $response->getBody()->write($tempFile->getContents());

        $filename = Collection::make(explode('/', $path))->last();

        return $response->withHeader('Content-Type', 'application/zip')
            ->withHeader('Content-Disposition', sprintf(
                'attachment; filename="%s.zip"',
                $filename == '.' ? 'Home' : $filename
            ));
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
        $pattern = sprintf('/^%s\/?/', preg_quote($path, '/'));

        return preg_replace($pattern, '', $file->getPathname());
    }
}
