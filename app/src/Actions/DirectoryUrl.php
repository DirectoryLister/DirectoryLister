<?php

declare(strict_types=1);

namespace App\Actions;

use App\Support\Str;
use DI\Container;

class DirectoryUrl
{
    public function __construct(
        private Container $container,
        private IsDirectLink $isDirectLink,
    ) {}

    /**
     * Return a URL for a directory, direct-linked if applicable.
     *
     * @param non-empty-string $directorySeparator
     */
    public function __invoke(string $path, string $directorySeparator = DIRECTORY_SEPARATOR): string
    {
        $escaped = Str::escape($path, $directorySeparator);
        $fullPath = $this->container->call('full_path', ['path' => $path]);

        return ($this->isDirectLink)($fullPath) ? $escaped : sprintf('?dir=%s', $escaped);
    }
}
