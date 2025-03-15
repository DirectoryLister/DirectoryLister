<?php

namespace App\ViewFunctions;

use App\Config;
use Tightenco\Collect\Support\Collection;

class Asset extends ViewFunction
{
    protected string $name = 'asset';

    /** Create a new Asset object. */
    public function __construct(
        private Config $config
    ) {}

    /** Return the path to an asset. */
    public function __invoke(string $path): string
    {
        $path = '/' . ltrim($path, '/');

        return 'app/assets/' . ltrim($path, '/');
    }
}
