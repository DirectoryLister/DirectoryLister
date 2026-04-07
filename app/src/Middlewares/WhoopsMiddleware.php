<?php

declare(strict_types=1);

namespace App\Middlewares;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\RunInterface;

class WhoopsMiddleware
{
    #[Inject('debug')]
    private string $debug;

    public function __construct(
        private RunInterface $whoops,
        private PrettyPageHandler $pageHandler,
        private JsonResponseHandler $jsonHandler
    ) {}

    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        if (! (bool) filter_var($this->debug, FILTER_VALIDATE_BOOL)) {
            return $handler->handle($request);
        }

        $this->pageHandler->setPageTitle(
            sprintf('%s • Directory Lister', $this->pageHandler->getPageTitle())
        );

        $this->whoops->pushHandler($this->pageHandler);

        if (in_array('application/json', explode(',', (string) $request->getHeaderLine('Accept')))) {
            $this->whoops->pushHandler($this->jsonHandler);
        }

        $this->whoops->register();

        return $handler->handle($request);
    }
}
