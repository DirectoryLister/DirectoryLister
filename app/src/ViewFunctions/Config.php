<?php

namespace App\ViewFunctions;

use PHLAK\Config\Config as AppConfig;

class Config extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'config';

    /** @var \PHLAK\Config\Config */
    protected $config;

    /**
     * Create a new Config object.
     *
     * @param \PHLAK\Config\Config $config
     */
    public function __construct(AppConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Retrieve an item from the view config.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function __invoke(string $key, $default = null)
    {
        return $this->config->split('app')->get($key, $default);
    }
}
