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
    public function __invoke(string $path = '/', string $basePath = ''): string
    {
        return $this->getRelativePath($path, $basePath);
    }

    /** Return the path relative to the base path for a complete path. */
    protected function getRelativePath(string $path, string $basePath): string
    {
        $basePathLen = strlen($basePath);

        if (strlen($path) < $basePathLen || substr($path, 0, $basePathLen) != $basePath) {
            $pathToReturn = $path;
        } else {
            $pathToReturn = substr_replace($path, '', 0, $basePathLen);
        }

        return $this->escape($pathToReturn);
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
