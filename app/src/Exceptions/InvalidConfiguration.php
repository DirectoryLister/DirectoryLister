<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class InvalidConfiguration extends RuntimeException
{
    /** Create an exception from a configuration option and value. */
    public static function fromConfig(string $option, mixed $value): self
    {
        return new static(
            sprintf("Unknown value %s for configuration option '%s'", var_export($value, true), $option)
        );
    }
}
