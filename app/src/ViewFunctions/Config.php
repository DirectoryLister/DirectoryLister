<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use App\Config as AppConfig;

class Config extends ViewFunction
{
    protected string $name = 'config';

    public function __construct(
        private AppConfig $config
    ) {}

    /**
     * Retrieve an item from the view config.
     *
     * @param mixed $default
     *
     * @return mixed
     */
    public function __invoke(string $key, $default = null)
    {
        return $this->config->get($key, $default);
    }
}
