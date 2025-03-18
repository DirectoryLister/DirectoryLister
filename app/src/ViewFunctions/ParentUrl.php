<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use App\Support\Str;

class ParentUrl extends ViewFunction
{
    protected string $name = 'parent_url';

    /** @param non-empty-string $directorySeparator */
    public function __construct(
        private string $directorySeparator = DIRECTORY_SEPARATOR
    ) {}

    /** Get the parent directory for a given path. */
    public function __invoke(string $path): string
    {
        $parentDir = Str::explode($path, $this->directorySeparator)->map(
            static fn (string $segment): string => rawurlencode($segment)
        )->filter(
            static fn (?string $value): bool => $value !== null
        )->slice(0, -1)->implode($this->directorySeparator);

        if ($parentDir === '') {
            return '.';
        }

        return sprintf('?dir=%s', $parentDir);
    }
}
