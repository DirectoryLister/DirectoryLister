<?php

namespace App\ViewFunctions;

class Asset extends ViewFunction
{
    /** @const Constant description */
    protected const ASSET_PATH = '/app/dist/';

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
        $assetPath = dirname($_SERVER['SCRIPT_NAME']) . self::ASSET_PATH . ltrim($path, '/');

        return '/' . ltrim($assetPath, '/');
    }
}
