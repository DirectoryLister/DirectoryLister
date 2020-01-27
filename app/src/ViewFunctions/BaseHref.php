<?php

namespace App\ViewFunctions;

class BaseHref extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'base_href';

    /**
     * Return the URL for a given path.
     *
     * @return string
     */
    public function __invoke(): string
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $baseHref = sprintf('%s://%s%s', $protocol, $_SERVER['HTTP_HOST'] ?? 'localhost', dirname($_SERVER['SCRIPT_NAME']));

        return rtrim($baseHref, '/') . '/';
    }
}
