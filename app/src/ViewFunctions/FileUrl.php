<?php

namespace App\ViewFunctions;

class FileUrl extends Url
{
    /** @var string The function name */
    protected $name = 'file_url';

    /** Return the URL for a given path and action. */
    public function __invoke(string $path = '/'): string
    {
        $path = $this->stripLeadingSlashes($path);
        if (is_file($path)) {
            return 'index.php?download=' . $this->escape($path);
        }

        if ($path === '') {
            return '';
        }

        return sprintf('?dir=%s', $this->escape($path));
    }
}
