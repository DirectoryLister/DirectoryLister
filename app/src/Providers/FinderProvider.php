<?php

namespace App\Providers;

use App\SortMethods;
use Closure;
use DI\Container;
use PHLAK\Config\Config;
use RuntimeException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Tightenco\Collect\Support\Collection;

class FinderProvider
{
    /** @const Application paths to be hidden */
    protected const APP_FILES = ['app', 'index.php'];

    /** @const Array of sort options mapped to their respective methods  */
    public const SORT_METHODS = [
        'accessed' => SortMethods\Accessed::class,
        'changed' => SortMethods\Changed::class,
        'modified' => SortMethods\Modified::class,
        'name' => SortMethods\Name::class,
        'natural' => SortMethods\Natural::class,
        'type' => SortMethods\Type::class,
    ];

    /** @var Config Application config */
    protected $config;

    /** @var Container The application container */
    protected $container;

    /**
     * Create a new ConfigProvider object.
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
     * Initialize and register the Finder component.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $finder = Finder::create()->followLinks();
        $finder->ignoreVCS($this->config->get('app.hide_vcs_files', false));
        $finder->filter(function (SplFileInfo $file) {
            foreach ($this->hiddenFiles() as $hiddenPath) {
                if (strpos($file->getRealPath(), $hiddenPath) === 0) {
                    return false;
                }
            }

            return true;
        });

        $sortOrder = $this->config->get('app.sort_order', 'type');
        if ($sortOrder instanceof Closure) {
            $finder->sort($sortOrder);
        } else {
            if (! array_key_exists($sortOrder, self::SORT_METHODS)) {
                throw new RuntimeException("Invalid sort option '{$sortOrder}'");
            }

            $this->container->call(self::SORT_METHODS[$sortOrder], [$finder]);
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
            return glob(
                $this->container->get('base_path') . '/' . $file,
                GLOB_BRACE | GLOB_NOSORT
            );
        })->flatten()->map(function (string $file) {
            return realpath($file);
        })->unique();
    }
}
