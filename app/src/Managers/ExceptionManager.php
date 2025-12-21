<?php

declare(strict_types=1);

namespace App\Managers;

use App\Exceptions\ErrorHandler;
use DI\Attribute\Inject;
use DI\Container;
use Slim\App;

class ExceptionManager
{
    #[Inject('debug')]
    private string $debug;

    /** @param App<Container> $app */
    public function __construct(
        private App $app,
    ) {}

    /** Set up and configure exception handling. */
    public function __invoke(): void
    {
        if ((bool) filter_var($this->debug, FILTER_VALIDATE_BOOL)) {
            return;
        }

        $errorMiddleware = $this->app->addErrorMiddleware(true, true, true);
        $errorMiddleware->setDefaultErrorHandler(ErrorHandler::class);
    }
}
