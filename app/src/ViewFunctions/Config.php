<?php

namespace App\ViewFunctions;

use App\Config as AppConfig;

class Config extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'config';

    /** @var AppConfig The application configuration */
    protected $config;

    /** Create a new Config object. */
    public function __construct(AppConfig $config)
    {
        $this->config = $config;
    }

    /** Retrieve an item from the view config. */
    public function __invoke(string $key, $default = null)
    {
        return $this->config->get($key, $default);
    }
}
