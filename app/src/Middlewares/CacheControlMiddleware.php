<?php

namespace App\Middlewares;

use App\Config;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CacheControlMiddleware
{
    /** Create a new CacheControlMiddleware object. */
    public function __construct(
        private Config $config
    ) {}

    /** Invoke the CacheControlMiddleware class. */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        foreach ($this->config->get('http_cache') as $contentType => $age) {
            if (in_array($contentType, $response->getHeader('Content-Type'))) {
                return $response->withHeader('Cache-Control', sprintf('max-age=%d, private, must-revalidate', $age));
            }
        }

        return $response->withHeader('Cache-Control', 'max-age=0, private, must-revalidate');
    }
}
