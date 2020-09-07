<?php

namespace App\ViewFunctions;

abstract class ViewFunction
{
    /** @var string The function name */
    protected $name = '';

    /** Get the function name. */
    public function name(): string
    {
        return $this->name;
    }
}
