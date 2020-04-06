<?php

namespace App\ViewFunctions;

use App\Support\Str;
use RuntimeException;

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
     * Return the URL for a given path and action.
     *
     * @param string      $path
     * @param string|null $action
     *
     * @return string
     */
    public function __invoke(string $path = '/', string $action = null): string
    {
        $path = preg_replace('/^.?(\/|\\\)+/', '', $path);

        switch ($action) {
            case null:
                return $this->escape($path);

            case 'dir':
                return empty($path) ? '' : sprintf('?dir=%s', $this->escape($path));

            case 'info':
                return empty($path) ? '?info=.' : sprintf('?info=%s', $this->escape($path));

            case 'zip':
                return empty($path) ? '?zip=.' : sprintf('?zip=%s', $this->escape($path));

            default:
                throw new RuntimeException(sprintf('Invalid action "%s"', $action));
        }
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
