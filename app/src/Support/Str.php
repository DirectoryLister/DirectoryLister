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

    /**
     * Escape URL characters in path segments.
     *
     * @param non-empty-string $delimiter
     */
    public static function escape(string $path, string $delimiter): string
    {
        return static::explode($path, $delimiter)->map(
            static fn (string $segment): string => rawurlencode($segment)
        )->implode($delimiter);
    }
}
