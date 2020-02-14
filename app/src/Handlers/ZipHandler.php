<?php

namespace App\Handlers;

use App\TemporaryFile;
use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Finder\Finder;
use Tightenco\Collect\Support\Collection;
use ZipArchive;

class ZipHandler
{
    /** @var Container The application container */
    protected $container;

    /** @var Finder The Finder Component */
    protected $finder;

    /**
     * Create a new ZipHandler object.
     *
     * @param \DI\Container      $container
     * @param \PhpCsFixer\Finder $finder
     */
    public function __construct(Container $container, Finder $finder)
    {
        $this->container = $container;
        $this->finder = $finder;
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

        if (! realpath($path)) {
            return $response->withStatus(404, 'File not found');
        }

        $tempFile = new TemporaryFile(
            $this->container->get('base_path') . '/app/cache'
        );

        $zip = new ZipArchive;
        $zip->open((string) $tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($this->finder->in($path) as $file) {
            if ($file->isFile()) {
                $zip->addFile($file->getRealPath(), $file->getPathname());
            }
        }

        $zip->close();

        $response->getBody()->write($tempFile->getContents());

        return $response->withHeader('Content-Type', 'application/zip')
            ->withHeader('Content-Disposition', sprintf(
                'attachment; filename="%s.zip"',
                Collection::make(explode('/', $path))->last()
            ));
    }
}
