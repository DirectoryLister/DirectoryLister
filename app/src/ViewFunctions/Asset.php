<?php

namespace App\ViewFunctions;

use App\Config;
use Tightenco\Collect\Support\Collection;

class Asset extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'asset';

    /** @var Config The application configuration */
    protected $config;

    /** Create a new Asset object. */
    public function __construct(Config $container)
    {
        $this->config = $container;
    }

    /** Return the path to an asset. */
    public function __invoke(string $path): string
    {
        $path = '/' . ltrim($path, '/');

        if ($this->mixManifest()->has($path)) {
            $path = $this->mixManifest()->get($path);
        }

        return 'app/assets/' . ltrim($path, '/');
    }

    /** Return the mix manifest collection. */
    protected function mixManifest(): Collection
    {
        $mixManifest = $this->config->get('asset_path') . '/mix-manifest.json';

        if (! is_file($mixManifest)) {
            return new Collection;
        }

        return Collection::make(
            json_decode(file_get_contents($mixManifest), true) ?? []
        );
    }
}
