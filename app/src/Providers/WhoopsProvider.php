<?php

namespace App\Providers;

use DI\Container;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
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
        $this->container->set(PrettyPageHandler::class, new PrettyPageHandler);
        $this->container->set(JsonResponseHandler::class, new JsonResponseHandler);
        $this->container->set(RunInterface::class, new Run);
    }
}
