<?php

namespace App\ViewFunctions;

class FileUrl extends Url
{
    protected string $name = 'file_url';

    /** Return the URL for a given path and action. */
    public function __invoke(string $path = '/'): string
    {
        if (is_file($path)) {
            return sprintf('?file=%s', $this->escape($this->normalizePath($path)));
        }

        $path = $this->normalizePath($path);

        if ($path === '') {
            return '';
        }

        return sprintf('?dir=%s', $this->escape($path));
    }
}
