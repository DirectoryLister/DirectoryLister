<?php

namespace App\Bootstrap;

use App\Controllers;
use Slim\App;

class RouteManager
{
    /** Create a new RouteManager object. */
    public function __construct(
        private App $app
    ) {}

    /** Register the application routes. */
    public function __invoke(): void
    {
        $this->app->get('/[{path:.*}]', Controllers\IndexController::class);
    }
}
