<?php

namespace App\Providers;

use DI\Container;
use PHLAK\Config\Config;

class ConfigProvider
{
    /** @var Container The applicaiton container */
    protected $container;

    /**
     * Create a new ConfigProvider object.
     *
     * @param \DI\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Initialize and register the Config component.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $this->container->set(Config::class, Config::createFromDirectory('app/config'));
    }
}
