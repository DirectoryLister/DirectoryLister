<?php

namespace App\Controllers;

use DI\Container;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class IndexController
{
    /** @var Container Application container */
    protected $container;

    /**
     * Create a new IndexController object.
     *
     * @param \DI\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Invoke the IndexController.
     *
     * @param \Slim\Psr7\Request  $request
     * @param \Slim\Psr7\Response $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response)
    {
        switch (true) {
            case array_key_exists('info', $request->getQueryParams()):
                return $this->container->call(FileInfoController::class, [$request, $response]);

            case array_key_exists('search', $request->getQueryParams()):
                return $this->container->call(SearchController::class, [$request, $response]);

            case array_key_exists('zip', $request->getQueryParams()):
                return $this->container->call(ZipController::class, [$request, $response]);

            default:
                return $this->container->call(DirectoryController::class, [$request, $response]);
        }
    }
}
