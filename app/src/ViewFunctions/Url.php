<?php

namespace App\ViewFunctions;

use DI\Container;

class Url extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'url';

    /** @var Container The application container */
    protected $container;

    /**
     * Create a new Breadcrumbs object.
     *
     * @param \DI\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
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

        if ($this->container->get('rewrite')) {
            $relativeRoot = substr($this->container->get('base_path'), strlen($_SERVER['DOCUMENT_ROOT']));
            return ($relativeRoot . '/' . ($path == '/' ? '' : $path));
        }

        if (is_file($path)) {
            return $path;
        }

        return empty($path) ? '' : sprintf('?dir=%s', $path);
    }
}
