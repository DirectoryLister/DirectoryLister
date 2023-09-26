<?php

namespace App;

use DI\Container;
use DI\NotFoundException;

class Config
{
    /** Create a new Config object. */
    public function __construct(
        private Container $container
    ) {}

    /**
     * Get the value of a configuration variable.
     *
     * @param mixed $default
     *
     * @return mixed
     */
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
