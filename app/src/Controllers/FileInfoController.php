<?php

namespace App\Controllers;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use SplFileInfo;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FileInfoController
{
    /** @var Container The application container */
    protected $container;

    /** @var CacheInterface The application cache */
    protected $cache;

    /** @var TranslatorInterface Translator component */
    protected $translator;

    /**
     * Create a new FileInfoHandler object.
     *
     * @param \DI\Container                                      $container
     * @param \Symfony\Contracts\Cache\CacheInterface            $cache
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(
        Container $container,
        CacheInterface $cache,
        TranslatorInterface $translator
    ) {
        $this->container = $container;
        $this->cache = $cache;
        $this->translator = $translator;
    }

    /**
     * Invoke the FileInfoHandler.
     *
     * @param \Slim\Psr7\Request  $request
     * @param \Slim\Psr7\Response $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $path = $request->getQueryParams()['info'];

        $file = new SplFileInfo(
            realpath($this->container->get('base_path') . '/' . $path)
        );

        if (! $file->isFile()) {
            return $response->withStatus(404, $this->translator->trans('error.file_not_found'));
        }

        if ($file->getSize() >= $this->container->get('max_hash_size')) {
            return $response->withStatus(500, $this->translator->trans('error.file_size_exceeded'));
        }

        $response->getBody()->write($this->cache->get(
            sprintf('file-info-%s', sha1($file->getRealPath())),
            function () use ($file): string {
                return json_encode(['hashes' => $this->calculateHashes($file)]);
            }
        ));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Get an array of hashes for a file.
     *
     * @param \SplFileInfo $file
     *
     * @return array
     */
    protected function calculateHashes(SplFileInfo $file): array
    {
        return [
            'md5' => hash_file('md5', $file->getRealPath()),
            'sha1' => hash_file('sha1', $file->getRealPath()),
            'sha256' => hash_file('sha256', $file->getRealPath()),
        ];
    }
}
