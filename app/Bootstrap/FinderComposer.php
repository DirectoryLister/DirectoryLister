<?php

namespace App\Bootstrap;

use Closure;
use DI\Container;
use PHLAK\Config\Config;
use RuntimeException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Tightenco\Collect\Support\Collection;

class FinderComposer
{
    /** @const Application paths to be hidden */
    protected const APP_FILES = ['app', 'node_modules', 'vendor', 'index.php'];

    /** @var Config Application config */
    protected $config;

    /** @var Container The application container */
    protected $container;

    /**
     * Create a new FinderComposer object.
     *
     * @param \DI\Container        $container
     * @param \PHLAK\Config\Config $config
     */
    public function __construct(Container $container, Config $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * Setup the Finder component.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $finder = Finder::create()->depth(0)->followLinks();
        $finder->ignoreVCS($this->config->get('app.hide_vcs_files', false));
        $finder->filter(function (SplFileInfo $file) {
            return ! $this->hiddenFiles()->contains($file->getRealPath());
        });

        $sortOrder = $this->config->get('app.sort_order', 'type');
        if ($sortOrder instanceof Closure) {
            $finder->sort($sortOrder);
        } else {
            switch ($sortOrder) {
                case 'accessed':
                    $finder->sortByAccessedTime();
                    break;
                case 'changed':
                    $finder->sortByChangedTime();
                    break;
                case 'modified':
                    $finder->sortByModifiedTime();
                    break;
                case 'name':
                    $finder->sortByName();
                    break;
                case 'natural':
                    $finder->sortByName(true);
                    break;
                case 'type':
                    $finder->sortByType();
                    break;
                default:
                    throw new RuntimeException("Invalid sort option '{$sortOrder}'");
            }
        }

        if ($this->config->get('app.reverse_sort', false)) {
            $finder->reverseSorting();
        }

        $this->container->set(Finder::class, $finder);
    }

    /**
     * Get a collection of hidden files.
     *
     * @return \Tightenco\Collect\Support\Collection
     */
    protected function hiddenFiles(): Collection
    {
        return Collection::make(
            $this->config->get('app.hidden_files', [])
        )->when($this->config->get('app.hide_app_files', true), function (Collection $collection) {
            return $collection->merge(self::APP_FILES);
        })->map(function (string $file) {
            return glob($file, GLOB_BRACE | GLOB_NOSORT);
        })->flatten()->map(function (string $file) {
            return realpath($file);
        });
    }
}
