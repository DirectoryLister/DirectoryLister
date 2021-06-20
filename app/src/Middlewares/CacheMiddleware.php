<?php

namespace App\Middlewares;

use App\Config;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CacheMiddleware
{
    /** @var Config The application configuration */
    protected $config;

    /** Create a new CacheMiddleware. */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /** Invoke the CacheMiddleware class. */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        foreach ($this->config->get('http_cache') as $contentType => $age) {
            if (in_array($contentType, $response->getHeader('Content-Type'))) {
                return $response->withHeader('Cache-Control', sprintf('max-age=%d', $age));
            }
        }

        return $response->withHeader('Cache-Control', 'max-age=0, must-revalidate');
    }
}
