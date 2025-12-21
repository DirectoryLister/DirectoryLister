<?php

declare(strict_types=1);

namespace App\Middlewares;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CacheControlMiddleware
{
    #[Inject('http_cache')]
    private array $httpCache;

    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        foreach ($this->httpCache as $contentType => $age) {
            if (in_array($contentType, $response->getHeader('Content-Type'))) {
                return $response->withHeader('Cache-Control', sprintf('max-age=%d, private, must-revalidate', $age));
            }
        }

        return $response->withHeader('Cache-Control', 'max-age=0, private, must-revalidate');
    }
}
