<?php

namespace App\ViewFunctions;

class FileUrl extends Url
{
    /** @var string The function name */
    protected $name = 'file_url';

    /**
     * Return the URL for a given path and action.
     *
     * @param string $path
     *
     * @return string
     */
    public function __invoke(string $path = '/'): string
    {
        $path = preg_replace('/^.?(\/|\\\)+/', '', $path);

        if (is_file($path)) {
            return $this->escape($path);
        }

        return empty($path) ? '' : sprintf('?dir=%s', $this->escape($path));
    }
}
