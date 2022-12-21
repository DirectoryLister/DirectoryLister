<?php

namespace App\ViewFunctions;

class FileUrl extends Url
{
    protected string $name = 'file_url';

    /** Return the URL for a given path and action. */
    public function __invoke(string $path = '/'): string
    {
        $path = $this->stripLeadingSlashes($path);

        if (is_file($path)) {
            return $this->escape($path);
        }

        if ($path === '') {
            return '';
        }

        return sprintf('?dir=%s', $this->escape($path));
    }
}
