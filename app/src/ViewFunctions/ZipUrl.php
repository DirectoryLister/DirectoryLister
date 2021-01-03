<?php

namespace App\ViewFunctions;

class ZipUrl extends Url
{
    /** @var string The function name */
    protected $name = 'zip_url';

    /** Return the URL for a given path and action. */
    public function __invoke(string $path = '/', string $basePath = ''): string
    {
        $path = $this->getRelativePath($path, $basePath);

        if ($path === '') {
            return '?zip=';
        }

        return sprintf('?zip=%s', $path);
    }
}
