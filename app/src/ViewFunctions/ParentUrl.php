<?php

namespace App\ViewFunctions;

use App\Support\Str;

class ParentUrl extends Url
{
    /** @var string The function name */
    protected $name = 'parent_url';

    /** Get the parent directory for a given path. */
    public function __invoke(string $path = '/', string $basePath = ''): string
    {
        $path = $this->getRelativePath($path, $basePath);

        $parentDir = Str::explode($path, $this->directorySeparator)
            ->filter(static function (?string $value): bool {
                return $value !== null; })
            ->slice(0, -1)->implode($this->directorySeparator);

        if ($parentDir === '') {
            return '.';
        }

        return sprintf('?dir=%s', $parentDir);
    }
}
