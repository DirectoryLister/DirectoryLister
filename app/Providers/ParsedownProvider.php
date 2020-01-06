<?php

namespace App\Providers;

use DI\Container;
use Parsedown;

class ParsedownProvider
{
    /** @var Container The applicaiton container */
    protected $container;

    /**
     * Create a new ParsedownProvider object.
     *
     * @param \DI\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Initialize and register the Parsedown component.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $this->container->set(Parsedown::class, new Parsedown);
    }
}
