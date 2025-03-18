<?php

declare(strict_types=1);

namespace App\Controllers;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class IndexController
{
    public function __construct(
        private Container $container
    ) {}

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $firstQueryParam = array_key_first($request->getQueryParams());

        $controller = match ($firstQueryParam) {
            'file' => FileController::class,
            'info' => FileInfoController::class,
            'search' => SearchController::class,
            'zip' => ZipController::class,
            default => DirectoryController::class,
        };

        return $this->container->call($controller, [$request, $response]);
    }
}
