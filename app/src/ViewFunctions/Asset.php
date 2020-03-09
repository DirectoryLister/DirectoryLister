<?php

namespace App\ViewFunctions;

use DI\Container;
use Tightenco\Collect\Support\Collection;

class Asset extends ViewFunction
{
    /** @const Constant description */
    protected const ASSET_PATH = 'app/assets/';

    /** @var string The function name */
    protected $name = 'asset';

    /** @var Container The application container */
    protected $container;

    /**
     * Create a new Asset object.
     *
     * @param \DI\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Return the path to an asset.
     *
     * @param string $path
     *
     * @return string
     */
    public function __invoke(string $path): string
    {
        $path = '/' . ltrim($path, '/');

        if ($this->mixManifest()->has($path)) {
            $path = $this->mixManifest()->get($path);
        }

        return self::ASSET_PATH . ltrim($path, '/');
    }

    /**
     * Return the mix manifest collection.
     *
     * @return \Tightenco\Collect\Support\Collection
     */
    protected function mixManifest(): Collection
    {
        $mixManifest = $this->container->get('base_path') . '/' . self::ASSET_PATH . 'mix-manifest.json';

        if (! is_file($mixManifest)) {
            return new Collection;
        }

        return Collection::make(
            json_decode(file_get_contents($mixManifest), true) ?? []
        );
    }
}
