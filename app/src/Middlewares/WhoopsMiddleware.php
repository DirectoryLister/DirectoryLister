<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\RunInterface;

class WhoopsMiddleware
{
    /** @var RunInterface The Whoops component */
    protected $whoops;

    /** @var PrettyPageHandler The pretty page handler */
    protected $pageHandler;

    /** @var JsonResponseHandler The JSON response handler */
    protected $jsonHandler;

    /** Create a new WhoopseMiddleware object. */
    public function __construct(
        RunInterface $whoops,
        PrettyPageHandler $pageHandler,
        JsonResponseHandler $jsonHandler
    ) {
        $this->whoops = $whoops;
        $this->pageHandler = $pageHandler;
        $this->jsonHandler = $jsonHandler;
    }

    /** Invoke the WhoopseMiddleware class. */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $this->pageHandler->setPageTitle(
            sprintf('%s • Directory Lister', $this->pageHandler->getPageTitle())
        );

        $this->whoops->pushHandler($this->pageHandler);

        if (in_array('application/json', explode(',', $request->getHeaderLine('Accept')))) {
            $this->whoops->pushHandler($this->jsonHandler);
        }

        $this->whoops->register();

        return $handler->handle($request);
    }
}
