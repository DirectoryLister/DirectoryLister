<?php

namespace App\Bootstrap;

use PHLAK\Config\Config;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Tightenco\Collect\Support\Collection;

class FilesComposer
{
    /** @var Config Application config */
    protected $config;

    /** @var Finder Symfony finder component */
    protected $finder;

    /** @var Collection Collection of hidden file paths */
    protected $hiddenFiles;

    public function __construct(Config $config, Finder $finder)
    {
        $this->config = $config;
        $this->finder = $finder;

        $this->hiddenFiles = Collection::make(
            $this->config->get('hidden_files', [])
        )->map(function (string $file) {
            return glob($file, GLOB_BRACE | GLOB_NOSORT);
        })->flatten()->map(function (string $file) {
            return realpath($file);
        });
    }

    /**
     * Setup the Finder component.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $this->finder->depth(0)->followLinks();
        $this->finder->ignoreVCS($this->config->get('ignore_vcs_files', false));
        $this->finder->filter(function (SplFileInfo $file) {
            return ! $this->hiddenFiles->contains($file->getRealPath());
        });
        $this->finder->sortByName(true)->sortByType();
    }
}
