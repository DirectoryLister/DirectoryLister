<?php

namespace App\ViewFunctions;

use Tightenco\Collect\Support\Collection;

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
        $assetPath = self::ASSET_PATH . $path;

        if ($this->mixManifest()->has($assetPath)) {
            return $this->mixManifest()->get($assetPath);
        }

        return $assetPath;
    }

    /**
     * Return the mix manifest collection.
     *
     * @return \Tightenco\Collect\Support\Collection
     */
    protected function mixManifest(): Collection
    {
        $mixManifest = $this->container->get('base_path') . '/mix-manifest.json';

        if (! is_file($mixManifest)) {
            return new Collection();
        }

        return Collection::make(
            json_decode(file_get_contents($mixManifest), true) ?? []
        );
    }
}
