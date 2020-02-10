<?php

namespace App\ViewFunctions;

use ParsedownExtra;

class Markdown extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'markdown';

    /**
     * Parses a string of markdown into HTML.
     *
     * @param string $string
     *
     * @return string
     */
    public function __invoke(string $string)
    {
        return ParsedownExtra::instance()->parse($string);
    }
}
