<?php

namespace App\Bootstrap;

use App\Controllers\DirectoryController;
use App\Controllers\FileInfoController;
use App\Controllers\SearchController;
use App\Controllers\ZipController;
use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class RouteManager
{
    /** Create a new RouteManager object. */
    public function __construct(
        private App $app,
        private Container $container
    ) {}

    /** Register the application routes. */
    public function __invoke(): void
    {
        $this->app->get('/[{path:.*}]', function (Request $request, Response $response): ResponseInterface {
            if (array_key_exists('info', $request->getQueryParams())) {
                return $this->container->call(FileInfoController::class, [$request, $response]);
            }

            if (array_key_exists('search', $request->getQueryParams())) {
                return $this->container->call(SearchController::class, [$request, $response]);
            }

            if (array_key_exists('zip', $request->getQueryParams())) {
                return $this->container->call(ZipController::class, [$request, $response]);
            }

            return $this->container->call(DirectoryController::class, [$request, $response]);
        });
    }
}
