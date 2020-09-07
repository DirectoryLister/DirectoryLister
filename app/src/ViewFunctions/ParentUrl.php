<?php

namespace App\ViewFunctions;

use App\Support\Str;

class ParentUrl extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'parent_url';

    /** @var string The directory separator */
    protected $directorySeparator;

    /** Create a new ParentUrl object. */
    public function __construct(string $directorySeparator = DIRECTORY_SEPARATOR)
    {
        $this->directorySeparator = $directorySeparator;
    }

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
