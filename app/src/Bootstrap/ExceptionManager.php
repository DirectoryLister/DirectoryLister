<?php

namespace App\Bootstrap;

use App\Config;
use App\Exceptions\ErrorHandler;
use Slim\App;

class ExceptionManager
{
    /** Create a new ExceptionManager object. */
    public function __construct(
        private App $app,
        private Config $config
    ) {}

    /** Set up and configure exception handling. */
    public function __invoke(): void
    {
        if ($this->config->get('debug')) {
            return;
        }

        $errorMiddleware = $this->app->addErrorMiddleware(true, true, true);
        $errorMiddleware->setDefaultErrorHandler(ErrorHandler::class);
    }
}
