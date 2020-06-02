<?php

namespace App\Factories;

use App\Exceptions\InvalidConfiguration;
use App\HiddenFiles;
use Closure;
use DI\Container;
use PHLAK\Splat\Glob;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Tightenco\Collect\Support\Collection;

class FinderFactory
{
    /** @var Container The application container */
    protected $container;

    /** @var HiddenFiles Collection of hidden files */
    protected $hiddenFiles;

    /** @var Glob Hidden files pattern cache */
    protected $pattern;

    /**
     * Create a new FinderFactory object.
     *
     * @param \DI\Container $container
     */
    public function __construct(Container $container, HiddenFiles $hiddenFiles)
    {
        $this->container = $container;
        $this->hiddenFiles = $hiddenFiles;
    }

    /**
     * Initialize and return the Finder component.
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function __invoke(): Finder
    {
        $finder = Finder::create()->followLinks();
        $finder->ignoreVCS($this->container->get('hide_vcs_files'));

        if ($this->hiddenFiles->isNotEmpty()) {
            $finder->filter(function (SplFileInfo $file): bool {
                return ! $this->isHidden($file);
            });
        }

        $sortOrder = $this->container->get('sort_order');
        if ($sortOrder instanceof Closure) {
            $finder->sort($sortOrder);
        } else {
            if (! array_key_exists($sortOrder, $this->container->get('sort_methods'))) {
                throw InvalidConfiguration::fromConfig('sort_order', $sortOrder);
            }

            $this->container->call($this->container->get('sort_methods')[$sortOrder], [$finder]);
        }

        if ($this->container->get('reverse_sort')) {
            $finder->reverseSorting();
        }

        return $finder;
    }

    /**
     * Determine if a file should be hidden.
     *
     * @param \Symfony\Component\Finder\SplFileInfo $file
     *
     * @return bool
     */
    protected function isHidden(SplFileInfo $file): bool
    {
        if (! isset($this->pattern)) {
            $this->pattern = Glob::pattern(sprintf('%s{%s}', Glob::escape(
                $this->container->get('base_path') . DIRECTORY_SEPARATOR
            ), $this->hiddenFiles->implode(',')));
        }

        return $this->pattern->matchStart($file->getRealPath());
    }
}
