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

        if ($this->mixManifest()->has($path)) {
            /** @var string $path */
            $path = $this->mixManifest()->get($path);
        }

        return 'app/assets/' . ltrim($path, '/');
    }

    /**
     * Return the mix manifest collection.
     *
     * @return Collection<string, string>
     */
    protected function mixManifest(): Collection
    {
        $mixManifest = $this->config->get('asset_path') . '/mix-manifest.json';

        if (! is_file($mixManifest)) {
            return new Collection;
        }

        return Collection::make(
            json_decode((string) file_get_contents($mixManifest), true) ?? []
        );
    }
}
