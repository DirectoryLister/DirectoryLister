<?php

namespace App\ViewFunctions;

class Asset extends ViewFunction
{
    /** @const Constant description */
    protected const ASSET_PATH = 'app/assets/';

    /** @var string The function name */
    protected $name = 'asset';

    /**
     * Return the path to an asset.
     *
     * @param string $path
     *
     * @return string
     */
    public function __invoke(string $path): string
    {
        return self::ASSET_PATH . ltrim($path, '/');
    }
}
