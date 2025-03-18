<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Controllers;
use DI\Container;
use Slim\App;

class RouteManager
{
    /** @param App<Container> $app */
    public function __construct(
        private App $app
    ) {}

    /** Register the application routes. */
    public function __invoke(): void
    {
        $this->app->get('/[{path:.*}]', Controllers\IndexController::class);
    }
}
