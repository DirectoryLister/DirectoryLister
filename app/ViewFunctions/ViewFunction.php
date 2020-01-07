<?php

namespace App\ViewFunctions;

use DI\Container;
use PHLAK\Config\Config;

abstract class ViewFunction
{
    /** @var string The function name */
    protected $name = '';

    /** @var Container The application container */
    protected $container;

    /** @var Config App configuration component */
    protected $config;

    /**
     * Create a new ViewFunction object.
     *
     * @param \PHLAK\Config\Config $config
     */
    public function __construct(Container $container, Config $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * Get the function name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
}
