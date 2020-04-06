<?php

namespace App\ViewFunctions;

use App\Support\Str;

class Url extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'url';

    /** @var string The directory separator */
    protected $directorySeparator;

    /**
     * Create a new Url object.
     *
     * @param string The directory separator
     */
    public function __construct(string $directorySeparator = DIRECTORY_SEPARATOR)
    {
        $this->directorySeparator = $directorySeparator;
    }

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

        return $this->escape($path);
    }

    /**
     * Escape URL characters in path segments.
     *
     * @param string $path
     *
     * @return string
     */
    protected function escape(string $path): string
    {
        return Str::explode($path, $this->directorySeparator)->map(
            function (string $segment): string {
                return urlencode($segment);
            }
        )->implode($this->directorySeparator);
    }
}
