<?php

namespace App\ViewFunctions;

use App\Support\Str;

class Url extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'url';

    /** @var string The directory separator */
    protected $directorySeparator;

    /** Create a new Url object. */
    public function __construct(string $directorySeparator = DIRECTORY_SEPARATOR)
    {
        $this->directorySeparator = $directorySeparator;
    }

    /** Return the URL for a given path. */
    public function __invoke(string $path = '/'): string
    {
        return $this->escape($this->stripLeadingSlashes($path));
    }

    /** Strip all leading slashes (and a single dot) from a path. */
    protected function stripLeadingSlashes(string $path): string
    {
        return preg_replace('/^\.?(\/|\\\)+/', '', $path);
    }

    /** Escape URL characters in path segments. */
    protected function escape(string $path): string
    {
        return Str::explode($path, $this->directorySeparator)->map(
            static function (string $segment): string {
                return rawurlencode($segment);
            }
        )->implode($this->directorySeparator);
    }
}
