<?php

declare(strict_types=1);

namespace App;

use DI\Container;
use DI\NotFoundException;

class Config
{
    public function __construct(
        private Container $container
    ) {}

    /** Get the value of a configuration variable. */
    public function get(string $key, mixed $default = null): mixed
    {
        try {
            $value = $this->container->get($key);
        } catch (NotFoundException) {
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
