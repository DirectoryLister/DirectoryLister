<?php

namespace App\Bootstrap;

use Closure;
use PHLAK\Config\Config;
use RuntimeException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Tightenco\Collect\Support\Collection;

class FilesComposer
{
    /** @const Application paths to be hidden */
    protected const APP_FILES = ['app', 'vendor', 'index.php'];

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
        )->when($config->get('hide_app_files', true), function ($collection) {
            return $collection->merge(self::APP_FILES);
        })->map(function (string $file) {
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

        $sortOrder = $this->config->get('sort_order', 'name');
        if ($sortOrder instanceof Closure) {
            $this->finder->sort($sortOrder);
        } else {
            switch ($sortOrder) {
                case 'accessed':
                    $this->finder->sortByAccessedTime();
                    break;
                case 'changed':
                    $this->finder->sortByChangedTime();
                    break;
                case 'modified':
                    $this->finder->sortByModifiedTime();
                    break;
                case 'name':
                    $this->finder->sortByName();
                    break;
                case 'natural':
                    $this->finder->sortByName(true);
                    break;
                case 'type':
                    $this->finder->sortByType();
                    break;
                default:
                    throw new RuntimeException("Invalid sort option '{$sortOrder}'");
            }
        }

        if ($this->config->get('reverse_sort', false)) {
            $this->finder->reverseSorting();
        }
    }
}
