<?php

namespace App\Exceptions;

use PHLAK\Config\Interfaces\ConfigInterface;
use Slim\App;

class ExceptionManager
{
    /** @var App The application */
    protected $app;

    /** @var ConfigInterface The application config */
    protected $config;

    /**
     * Create a new ExceptionManager object.
     *
     * @param \Slim\App                                $app
     * @param \PHLAK\Config\Interfaces\ConfigInterface $config
     */
    public function __construct(App $app, ConfigInterface $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    /**
     * Set up and configure exception handling.
     *
     * @return void
     */
    public function __invoke(): void
    {
        if ($this->config->get('app.debug', false)) {
            return;
        }

        $errorMiddleware = $this->app->addErrorMiddleware(true, true, true);
        $errorMiddleware->setDefaultErrorHandler(ErrorHandler::class);
    }
}
