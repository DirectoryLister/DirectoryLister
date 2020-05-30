<?php

namespace App\Exceptions;

use RuntimeException;

class InvalidConfiguration extends RuntimeException
{
    /**
     * Createn an exception from a configiraton option and value.
     *
     * @param string $option
     * @param mixed  $value
     *
     * @return self
     */
    public static function fromConfig(string $option, $value): self
    {
        return new static(
            sprintf("Unknown value %s for configuration option '%s'", var_export($value, true), $option)
        );
    }
}
