<?php

namespace App\ViewFunctions;

use App\Support\Str;

class ParentUrl extends ViewFunction
{
    protected string $name = 'parent_url';

    /** Create a new ParentUrl object. */
    public function __construct(
        private string $directorySeparator = DIRECTORY_SEPARATOR
    ) {}

    /** Get the parent directory for a given path. */
    public function __invoke(string $path): string
    {
        $parentDir = Str::explode($path, $this->directorySeparator)->map(
            static function (string $segment): string {
                return rawurlencode($segment);
            }
        )->filter(static function (?string $value): bool {
            return $value !== null;
        })->slice(0, -1)->implode($this->directorySeparator);

        if ($parentDir === '') {
            return '.';
        }

        return sprintf('?dir=%s', $parentDir);
    }
}
