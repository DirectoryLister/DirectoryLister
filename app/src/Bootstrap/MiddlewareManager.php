<?php

namespace App\Bootstrap;

use App\Config;
use Slim\App;

class MiddlewareManager
{
    /** @var App The application */
    protected $app;

    /** @var Config The application configuration */
    protected $config;

    /**
     * Create a new MiddlwareManager object.
     *
     * @param \Slim\App   $app
     * @param \App\Config $config
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
    public function __invoke(): void
    {
        foreach ($this->config->get('middlewares') as $middleware) {
            $this->app->add($middleware);
        }
    }
}
