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
    public function __invoke(string $path = '/'): string
    {
        $path = preg_replace('/^.?(\/|\\\)+/', '', $path);

        if (is_file($path)) {
            return $path;
        }

        return empty($path) ? '' : sprintf('?dir=%s', $path);
    }
}
