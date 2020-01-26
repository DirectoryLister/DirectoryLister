<?php

namespace App\Controllers;

use DI\Container;
use PHLAK\Config\Config;
use Slim\Psr7\Response;
use SplFileInfo;

class FileInfoController
{
    /** @var Container The application container */
    protected $container;

    /** @var Config App configuration component */
    protected $config;

    /**
     * Create a new FileInfoController object.
     *
     * @param \DI\Container        $container
     * @param \PHLAK\Config\Config $config
     */
    public function __construct(Container $container, Config $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * Invoke the FileInfoController.
     *
     * @param \Slim\Psr7\Response $response
     * @param string              $path
     */
    public function __invoke(Response $response, string $path = '.')
    {
        $file = new SplFileInfo(
            realpath($this->container->get('base_path') . '/' . $path)
        );

        if (! $file->isFile()) {
            return $response->withStatus(404, 'File not found');
        }

        if ($file->getSize() >= $this->config->get('app.max_hash_size', 1000000000)) {
            return $response->withStatus(500, 'File size too large');
        }

        $response->getBody()->write(json_encode([
            'hashes' => [
                'md5' => hash('md5', file_get_contents($file->getPathname())),
                'sha1' => hash('sha1', file_get_contents($file->getPathname())),
                'sha256' => hash('sha256', file_get_contents($file->getPathname())),
            ]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
