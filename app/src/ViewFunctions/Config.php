<?php

namespace App\ViewFunctions;

class Config extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'config';

    /**
     * Retrieve an item from the view config.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function __invoke(string $key, $default = null)
    {
        return $this->config->split('app')->get($key, $default);
    }
}
