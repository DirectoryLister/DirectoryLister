<?php

namespace App\Middlewares;

use PHLAK\Config\Config;
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

    /** @var Config The application config */
    protected $config;

    /**
     * Create a new WhoopseMiddleware object.
     *
     * @param \Whoops\RunInterface                $config
     * @param \Whoops\Handler\PrettyPageHandler   $pageHandler
     * @param \Whoops\Handler\JsonResponseHandler $jsonHandler
     * @param \PHLAK\Config\Config                $config
     */
    public function __construct(
        RunInterface $whoops,
        PrettyPageHandler $pageHandler,
        JsonResponseHandler $jsonHandler,
        Config $config
    ) {
        $this->whoops = $whoops;
        $this->pageHandler = $pageHandler;
        $this->jsonHandler = $jsonHandler;
        $this->config = $config;
    }

    /**
     * Invoke the WhoopseMiddleware class.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $this->pageHandler->addDataTable('Application Config', $this->config->split('app')->toArray());
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
