<?php

namespace App\Bootstrap;

use App\Controllers;
use Slim\App;

class RouteManager
{
    /** @var App The application */
    protected $app;

    /** Create a new RouteManager object. */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /** Register the application routes. */
    public function __invoke(): void
    {
        $this->app->get('/[{path:.*}]', Controllers\IndexController::class);
    }
}
