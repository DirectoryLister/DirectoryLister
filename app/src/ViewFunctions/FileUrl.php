<?php

namespace App\ViewFunctions;

class FileUrl extends Url
{
    /** @var string The function name */
    protected $name = 'file_url';

    /** Return the URL for a given path and action. */
    public function __invoke(string $path = '/', string $fileUrlPrefix = '', string $basePath = ''): string
    {
        $relPath = $this->getRelativePath($path, $basePath);

        if (is_file($path)) {
            return $fileUrlPrefix . $relPath;
        }

        if ($path === '') {
            return '';
        }

        return sprintf('?dir=%s', $relPath);
    }
}
