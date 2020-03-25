<?php

namespace App\ViewFunctions;

use DI\Container;
use DI\NotFoundException;

class Config extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'config';

    /** @var Container The application container */
    protected $container;

    /**
     * Create a new Config object.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
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
        try {
            return $this->container->get($key);
        } catch (NotFoundException $exception) {
            return $default;
        }
    }
}
