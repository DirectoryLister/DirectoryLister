<?php

declare(strict_types=1);

namespace App\Factories;

use App\Actions\IsHidden;
use App\Exceptions\InvalidConfiguration;
use App\HiddenFiles;
use Closure;
use DI\Attribute\Inject;
use DI\Container;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class FinderFactory
{
    private HiddenFiles $hiddenFiles;

    #[Inject('hide_vcs_files')]
    private bool $hideVcsFiles;

    #[Inject('hide_dot_files')]
    private bool $hideDotFiles;

    #[Inject('sort_order')]
    private string|Closure $sortOrder;

    #[Inject('sort_methods')]
    private array $sortMethods;

    #[Inject('reverse_sort')]
    private string $reverseSort;

    public function __construct(
        private Container $container,
        private IsHidden $isHidden,
    ) {
        $this->hiddenFiles = HiddenFiles::fromContainer($this->container);
    }

    public function __invoke(): Finder
    {
        $finder = Finder::create()->followLinks();
        $finder->ignoreVCS($this->hideVcsFiles);
        $finder->ignoreDotFiles($this->hideDotFiles);

        if ($this->hiddenFiles->isNotEmpty()) {
            $finder->filter(fn (SplFileInfo $file): bool => ! $this->isHidden->file($file));
        }

        if ($this->sortOrder instanceof Closure) {
            $finder->sort($this->sortOrder);
        } else {
            if (! array_key_exists($this->sortOrder, $this->sortMethods)) {
                throw InvalidConfiguration::forOption('sort_order', $this->sortOrder);
            }

            $this->container->call($this->sortMethods[$this->sortOrder], ['finder' => $finder]);
        }

        if (filter_var($this->reverseSort, FILTER_VALIDATE_BOOL)) {
            $finder->reverseSorting();
        }

        return $finder;
    }
}
