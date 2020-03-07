<?php

namespace App\Support;

use Tightenco\Collect\Support\Collection;

class Str
{
    /**
     * Explode a string by a string into a collection.
     *
     * @param string $string
     * @param string $delimiter
     *
     * @return \Tightenco\Collect\Support\Collection
     */
    public static function explode(string $string, string $delimiter): Collection
    {
        return Collection::make(explode($delimiter, $string));
    }
}
