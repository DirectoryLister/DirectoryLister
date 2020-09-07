<?php

namespace App;

use DI\Container;
use DI\NotFoundException;

class Config
{
    /** @var Container The application container */
    protected $container;

    /** Create a new Config object. */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /** Get the value of a configuration variable. */
    public function get(string $key, $default = null)
    {
        try {
            $value = $this->container->get($key);
        } catch (NotFoundException $exception) {
            return $default;
        }

        if (! is_string($value)) {
            return $value;
        }

        switch (strtolower($value)) {
            case 'true':
                return true;
            case 'false':
                return false;
            case 'null':
                return null;
        }

        return preg_replace('/^"(.*)"$/', '$1', $value);
    }
}
