<?php

namespace App\Bootstrap;

use App\Middlewares;
use Middlewares as HttpMiddlewares;
use PHLAK\Config\Config;
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

    /** @var Config The application config */
    protected $config;

    /**
     * Create a new MiddlwareManager object.
     *
     * @param \Slim\App            $app
     * @param \PHLAK\Config\Config $config
     */
    public function __construct(App $app, Config $config)
    {
        $this->app = $app;
        $this->config = $config;
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

        $this->app->add(new HttpMiddlewares\Expires(
            $this->config->get('app.http_expires', [
                'application/zip' => '+1 hour',
                'text/json' => '+1 hour',
            ])
        ));
    }
}
