<?php

namespace App\Bootstrap;

use DI\Container;
use PHLAK\Config\Config;

class ConfigComposer
{
    /** @var Container The applicaiton container */
    protected $container;

    /**
     * Create a new ConfigComposer object.
     *
     * @param \DI\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Set up the Config component.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $this->container->set(Config::class, new Config('app/config'));
    }
}
