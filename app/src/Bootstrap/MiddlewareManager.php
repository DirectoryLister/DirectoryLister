<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Config;
use DI\Container;
use Slim\App;

class MiddlewareManager
{
    /** @param App<Container> $app */
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
