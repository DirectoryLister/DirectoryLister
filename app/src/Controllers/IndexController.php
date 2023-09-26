<?php

namespace App\Controllers;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class IndexController
{
    /** Create a new IndexController object. */
    public function __construct(
        private Container $container
    ) {}

    /** Invoke the IndexController. */
    public function __invoke(Request $request, Response $response): ResponseInterface
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
