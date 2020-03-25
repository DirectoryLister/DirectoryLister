<?php

namespace App\Bootstrap;

use App\Exceptions\ErrorHandler;
use DI\Container;
use Slim\App;

class ExceptionManager
{
    /** @var App The application */
    protected $app;

    /** @var Container The application container */
    protected $container;

    /**
     * Create a new ExceptionManager object.
     *
     * @param \Slim\App     $app
     * @param \DI\Container $container
     */
    public function __construct(App $app, Container $container)
    {
        $this->app = $app;
        $this->container = $container;
    }

    /**
     * Set up and configure exception handling.
     *
     * @return void
     */
    public function __invoke(): void
    {
        if ($this->container->get('debug')) {
            return;
        }

        $errorMiddleware = $this->app->addErrorMiddleware(true, true, true);
        $errorMiddleware->setDefaultErrorHandler(ErrorHandler::class);
    }
}
