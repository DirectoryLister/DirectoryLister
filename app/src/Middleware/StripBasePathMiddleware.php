<?php

namespace App\Middleware;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StripBasePathMiddleware
{
    /** @var Container The application container */
    protected $container;

    /**
     * Create a new CanonicalizePathMiddleware object.
     *
     * @param \DI\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Canonicalize the received path variable.
     *
     * @param \Slim\Psr7\Request  $request
     * @param \Slim\Psr7\Response $response
     * @param callable            $next
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $path = $request->getUri()->getPath();

        $request = $request->withUri(
            $request->getUri()->withPath($this->stripBasePath($path))
        );

        return $handler->handle($request);
    }

    /**
     * Strip the base URL path from a path string.
     *
     * @param string $path
     *
     * @return string
     */
    protected function stripBasePath(string $path): string
    {
        $pattern = sprintf('/^%s/', preg_quote(dirname($_SERVER['SCRIPT_NAME']), '/'));

        return preg_replace($pattern, '', $path);
    }
}
