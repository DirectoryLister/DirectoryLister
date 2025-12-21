<?php

declare(strict_types=1);

namespace App\Functions;

use App\Support\Str;
use DI\Attribute\Inject;
use DI\Container;

class Url extends ViewFunction
{
    public string $name = 'url';

    #[Inject('files_path')]
    private string $filesPath;

    /** @param non-empty-string $directorySeparator */
    public function __construct(
        protected Container $container,
        private string $directorySeparator = DIRECTORY_SEPARATOR
    ) {}

    /** Return the URL for a given path. */
    public function __invoke(string $path = '/'): string
    {
        return $this->escape($this->normalizePath($path));
    }

    /** Strip base path and leading slashes (and a single dot) from a path */
    protected function normalizePath(string $path): string
    {
        return $this->stripLeadingSlashes($this->stripBasePath($path));
    }

    /** Strip the base path from the beginning of a path. */
    protected function stripBasePath(string $path): string
    {
        $basePath = $this->filesPath . $this->directorySeparator;

        $position = strpos($path, $basePath);

        if ($position !== 0) {
            return $path;
        }

        return (string) substr_replace($path, '', 0, strlen($basePath));
    }

    /** Strip all leading slashes (and a single dot) from a path. */
    protected function stripLeadingSlashes(string $path): string
    {
        return (string) preg_replace('/^\.?(\/|\\\)+/', '', $path);
    }

    /** Escape URL characters in path segments. */
    protected function escape(string $path): string
    {
        return Str::explode($path, $this->directorySeparator)->map(
            static fn (string $segment): string => rawurlencode($segment)
        )->implode($this->directorySeparator);
    }
}
