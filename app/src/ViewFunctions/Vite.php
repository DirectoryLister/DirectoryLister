<?php

namespace App\ViewFunctions;

use App\Config;
use Tightenco\Collect\Support\Collection;
use UnexpectedValueException;

class Vite extends ViewFunction
{
    protected string $name = 'vite';

    public function __construct(
        private Config $config,
    ) {}

    public function __invoke(array $assets): string
    {
        $manifest = $this->config->get('app_path') . '/manifest.json';

        $tags = is_file($manifest) ? $this->getBuildTags($manifest, $assets) : $this->getDevTags($assets);

        return $tags->implode("\n");
    }

    private function getBuildTags(string $manifest, array $assets): Collection
    {
        $manifest = json_decode(file_get_contents($manifest), flags: JSON_THROW_ON_ERROR);

        return Collection::make($assets)->map(
            fn (string $asset): string => match (mb_substr($asset, mb_strrpos($asset, '.'))) {
                '.js' => sprintf('<script type="module" src="app/%s"></script>', $manifest->{$asset}->file),
                '.css' => sprintf('<link rel="stylesheet" href="app/%s">', $manifest->{$asset}->file),
                default => throw new UnexpectedValueException(sprintf('Unsupported asset type: %s', $asset))
            }
        );
    }

    private function getDevTags(array $assets): Collection
    {
        return Collection::make($assets)->map(
            fn (string $asset): string => match (mb_substr($asset, mb_strrpos($asset, '.'))) {
                '.js' => sprintf('<script type="module" src="http://%s:5173/%s"></script>', $_SERVER['HTTP_HOST'], $asset),
                '.css' => sprintf('<link rel="stylesheet" href="http://%s:5173/%s">', $_SERVER['HTTP_HOST'], $asset),
                default => throw new UnexpectedValueException(sprintf('Unsupported asset type: %s', $asset))
            }
        )->prepend(sprintf('<script type="module" src="http://%s:5173/@vite/client"></script>', $_SERVER['HTTP_HOST']));
    }
}
