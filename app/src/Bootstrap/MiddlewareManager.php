<?php

namespace App\Bootstrap;

use App\Middlewares;
use Middlewares as HttpMiddlewares;
use Slim\App;
use Tightenco\Collect\Support\Collection;

class MiddlewareManager
{
    /** @const Array of application middlewares */
    protected const MIDDLEWARES = [
        Middlewares\WhoopsMiddleware::class
    ];

    /** @var App The application */
    protected $app;

    /**
     * Create a new MiddlwareManager object.
     *
     * @param \Slim\App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Register application middlewares.
     *
     * @return void
     */
    public function __invoke()
    {
        Collection::make(self::MIDDLEWARES)->each(
            function (string $middleware): void {
                $this->app->add($middleware);
            }
        );

        $this->app->add(new HttpMiddlewares\Expires([
            'application/zip' => '+1 hour',
            'text/json' => '+1 hour',
        ]));
    }
}
