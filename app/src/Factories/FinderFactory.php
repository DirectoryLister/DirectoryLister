<?php

namespace App\Factories;

use App\Config;
use App\Exceptions\InvalidConfiguration;
use App\HiddenFiles;
use Closure;
use DI\Container;
use PHLAK\Splat\Glob;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class FinderFactory
{
    /** @var Container The application container */
    protected $container;

    /** @var HiddenFiles Collection of hidden files */
    protected $hiddenFiles;

    /** @var Glob|null Hidden files pattern cache */
    protected $pattern;

    /** @var Config The application configuration */
    protected $config;

    /** Create a new FinderFactory object. */
    public function __construct(
        Container $container,
        Config $config,
        HiddenFiles $hiddenFiles
    ) {
        $this->container = $container;
        $this->config = $config;
        $this->hiddenFiles = $hiddenFiles;
    }

    /** Initialize and return the Finder component. */
    public function __invoke(): Finder
    {
        $finder = Finder::create()->followLinks();
        $finder->ignoreVCS($this->config->get('hide_vcs_files'));

        if ($this->hiddenFiles->isNotEmpty()) {
            $finder->filter(function (SplFileInfo $file): bool {
                return ! $this->isHidden($file);
            });
        }

        $sortOrder = $this->config->get('sort_order');
        if ($sortOrder instanceof Closure) {
            $finder->sort($sortOrder);
        } else {
            if (! array_key_exists($sortOrder, $this->config->get('sort_methods'))) {
                throw InvalidConfiguration::fromConfig('sort_order', $sortOrder);
            }

            $this->container->call($this->config->get('sort_methods')[$sortOrder], [$finder]);
        }

        if ($this->config->get('reverse_sort')) {
            $finder->reverseSorting();
        }

        return $finder;
    }

    /** Determine if a file should be hidden. */
    protected function isHidden(SplFileInfo $file): bool
    {
        if (! isset($this->pattern)) {
            $this->pattern = Glob::pattern(sprintf('%s{%s}', Glob::escape(
                $this->config->get('base_path') . DIRECTORY_SEPARATOR
            ), $this->hiddenFiles->implode(',')));
        }

        return $this->pattern->matchStart($file->getRealPath());
    }
}
