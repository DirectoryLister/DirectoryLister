<?php

namespace App\ViewFunctions;

use App\Support\Str;
use DI\Container;
use Tightenco\Collect\Support\Collection;

class Breadcrumbs extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'breadcrumbs';

    /** @var Container The application container */
    protected $container;

    /** @var string The directory separator */
    protected $directorySeparator;

    /**
     * Create a new Breadcrumbs object.
     *
     * @param \DI\Container $container
     */
    public function __construct(
        Container $container,
        string $directorySeparator = DIRECTORY_SEPARATOR
    ) {
        $this->container = $container;
        $this->directorySeparator = $directorySeparator;
    }

    /**
     * Build an array of breadcrumbs for a given path.
     *
     * @param string $path
     *
     * @return array
     */
    public function __invoke(string $path)
    {
        return Str::explode($path, $this->directorySeparator)->diff(
            explode($this->directorySeparator, $this->container->get('base_path'))
        )->filter(static function (string $crumb): bool {
            return ! in_array($crumb, [null, '.']);
        })->reduce(function (Collection $carry, string $crumb): Collection {
            return $carry->put($crumb, ltrim(
                $carry->last() . $this->directorySeparator . rawurlencode($crumb), $this->directorySeparator
            ));
        }, new Collection)->map(static function (string $path, string $name): string {
            return sprintf('?dir=%s', $path);
        });
    }
}
