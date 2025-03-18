<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Collection;

class Str
{
    /**
     * Explode a string by a string into a collection.
     *
     * @param non-empty-string $delimiter
     *
     * @return Collection<int, string>
     */
    public static function explode(string $string, string $delimiter): Collection
    {
        return Collection::make(explode($delimiter, $string));
    }
}
