<?php

namespace App\Support;

use Tightenco\Collect\Support\Collection;

class Str
{
    /**
     * Explode a string by a string into a collection.
     *
     * @return Collection<int, string>
     */
    public static function explode(string $string, string $delimiter): Collection
    {
        return Collection::make(explode($delimiter, $string));
    }
}
