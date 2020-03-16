<?php

namespace App\ViewFunctions;

use PHLAK\Config\Interfaces\ConfigInterface;

class Config extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'config';

    /** @var ConfigInterface The application configuration */
    protected $config;

    /**
     * Create a new Config object.
     *
     * @param \PHLAK\Config\Interfaces\ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
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
