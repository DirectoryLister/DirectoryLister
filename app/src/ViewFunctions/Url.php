<?php

namespace App\ViewFunctions;

class Url extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'url';

    /**
     * Return the URL for a given path.
     *
     * @param string $path
     *
     * @return string
     */
    public function __invoke(string $path): string
    {
        $url = dirname($_SERVER['SCRIPT_NAME']) . '/' . ltrim($path, '/');

        return '/' . trim($url, '/');
    }
}
