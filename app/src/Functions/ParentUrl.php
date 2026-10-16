<?php

declare(strict_types=1);

namespace App\Functions;

use App\Actions\DirectoryUrl;
use App\Support\Str;

class ParentUrl extends ViewFunction
{
    public string $name = 'parent_url';

    /** @param non-empty-string $directorySeparator */
    public function __construct(
        private DirectoryUrl $directoryUrl,
        private string $directorySeparator = DIRECTORY_SEPARATOR,
    ) {}

    /** Get the parent directory for a given path. */
    public function __invoke(string $path): string
    {
        $parentPath = Str::explode($path, $this->directorySeparator)->filter(
            static fn (?string $value): bool => $value !== null
        )->slice(0, -1)->implode($this->directorySeparator);

        if ($parentPath === '') {
            return '.';
        }

        return ($this->directoryUrl)($parentPath, $this->directorySeparator);
    }
}
