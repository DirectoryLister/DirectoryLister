<?php

namespace App\ViewFunctions;

class ZipUrl extends Url
{
    protected string $name = 'zip_url';

    /** Return the URL for a given path and action. */
    public function __invoke(string $path = '/'): string
    {
        $path = $this->stripLeadingSlashes($path);

        if ($path === '') {
            return '?zip=.';
        }

        return sprintf('?zip=%s', $this->escape($path));
    }
}
