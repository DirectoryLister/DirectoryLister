<?php

namespace App\Providers;

use DI\Container;
use Whoops\Run;
use Whoops\RunInterface;

class WhoopsProvider
{
    /** @var Container The applicaiton container */
    protected $container;

    /**
     * Create a new WhoopsProvider object.
     *
     * @param \DI\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Initialize and register the Whoops component.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $this->container->set(RunInterface::class, new Run);
    }
}
