<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use App\Config;
use Illuminate\Support\Collection;
use UnexpectedValueException;

class Vite extends ViewFunction
{
    protected string $name = 'vite';

    public function __construct(
        private Config $config,
    ) {}

    /** @param array<string> $assets */
    public function __invoke(array $assets): string
    {
        $tags = is_file($this->config->get('manifest_path')) ? $this->getBuildTags($assets) : $this->getDevTags($assets);

        return $tags->implode("\n");
    }

    /**
     * @param array<string> $assets
     *
     * @return Collection<int, string>
     */
    private function getBuildTags(array $assets): Collection
    {
        $manifest = json_decode((string) file_get_contents($this->config->get('manifest_path')), flags: JSON_THROW_ON_ERROR);

        return Collection::make($assets)->map(
            static fn (string $asset): string => match (mb_substr($asset, (int) mb_strrpos($asset, '.'))) {
                '.js' => sprintf('<script type="module" src="%s"></script>', $manifest->{$asset}->file),
                '.css' => sprintf('<link rel="stylesheet" href="%s">', $manifest->{$asset}->file),
                default => throw new UnexpectedValueException(sprintf('Unsupported asset type: %s', $asset))
            }
        );
    }

    /**
     * @param array<string> $assets
     *
     * @return Collection<int, string>
     */
    private function getDevTags(array $assets): Collection
    {
        return Collection::make($assets)->map(
            static fn (string $asset): string => match (mb_substr($asset, (int) mb_strrpos($asset, '.'))) {
                '.js' => sprintf('<script type="module" src="http://%s:5173/%s"></script>', $_SERVER['HTTP_HOST'], $asset),
                '.css' => sprintf('<link rel="stylesheet" href="http://%s:5173/%s">', $_SERVER['HTTP_HOST'], $asset),
                default => throw new UnexpectedValueException(sprintf('Unsupported asset type: %s', $asset))
            }
        )->prepend(sprintf('<script type="module" src="http://%s:5173/@vite/client"></script>', $_SERVER['HTTP_HOST']));
    }
}
