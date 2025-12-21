<?php

declare(strict_types=1);

namespace App\Functions;

class ZipUrl extends Url
{
    public string $name = 'zip_url';

    public function __invoke(string $path = '/'): string
    {
        $path = $this->normalizePath($path);

        if ($path === '') {
            return '?zip=.';
        }

        return sprintf('?zip=%s', $this->escape($path));
    }
}
