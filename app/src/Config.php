<?php

namespace App;

use DI\Container;
use DI\NotFoundException;

class Config
{
    /** @var Container The application container */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Get the value of a configuration vairable.
     *
     * @param string $key     The unique configuration variable ID
     * @param mixed  $default Default value to return if no environment variable is set
     *
     * @return mixed
     */
    public function get($key, $default = null)
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
