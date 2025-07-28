<?php

declare(strict_types=1);

namespace App\Factories;

use App\Config;
use App\Exceptions\InvalidConfiguration;
use App\HiddenFiles;
use Closure;
use DI\Container;
use PHLAK\Splat\Glob;
use PHLAK\Splat\Pattern;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class FinderFactory
{
    /** Hidden files pattern cache */
    private ?Pattern $pattern = null;

    public function __construct(
        private Container $container,
        private Config $config,
        private HiddenFiles $hiddenFiles
    ) {}

    public function __invoke(): Finder
    {
        $finder = Finder::create()->followLinks();
        $finder->ignoreVCS($this->config->get('hide_vcs_files'));
        $finder->ignoreDotFiles($this->config->get('hide_dot_files'));

        if ($this->hiddenFiles->isNotEmpty()) {
            $finder->filter(fn (SplFileInfo $file): bool => ! $this->isHidden($file));
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
    private function isHidden(SplFileInfo $file): bool
    {
        if (! $this->pattern instanceof Pattern) {
            $this->pattern = Pattern::make(sprintf('%s{%s}', Pattern::escape(
                $this->config->get('files_path') . DIRECTORY_SEPARATOR
            ), $this->hiddenFiles->implode(',')));
        }

        return Glob::match($this->pattern, (string) $file->getRealPath());
    }
}
