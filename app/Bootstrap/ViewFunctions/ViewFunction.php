<?php

namespace App\Bootstrap\ViewFunctions;

use PHLAK\Config\Config;

abstract class ViewFunction
{
    /** @var string The function name */
    protected $name = '';

    /** @var Config App configuration component */
    protected $config;

    /**
     * Create a new ViewFunction object.
     *
     * @param \PHLAK\Config\Config $config
     */
    public function __construct(Config $config)
    {
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
