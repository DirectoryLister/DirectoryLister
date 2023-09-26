<?php

namespace App\Bootstrap;

use App\Config;
use Slim\App;

class MiddlewareManager
{
    /** Create a new MiddlwareManager object. */
    public function __construct(
        private App $app,
        private Config $config
    ) {}

    /** Register application middlewares. */
    public function __invoke(): void
    {
        foreach ($this->config->get('middlewares') as $middleware) {
            $this->app->add($middleware);
        }
    }
}
