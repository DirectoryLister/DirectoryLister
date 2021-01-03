<?php

namespace App\ViewFunctions;

use App\Config;
use App\Support\Str;
use Tightenco\Collect\Support\Collection;

class Breadcrumbs extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'breadcrumbs';

    /** @var Config The application configuration */
    protected $config;

    /** @var string The directory separator */
    protected $directorySeparator;

    /** Create a new Breadcrumbs object. */
    public function __construct(
        Config $config,
        string $directorySeparator = DIRECTORY_SEPARATOR
    ) {
        $this->config = $config;
        $this->directorySeparator = $directorySeparator;
    }

    /** Build a collection of breadcrumbs for a given path. */
    public function __invoke(string $path): Collection
    {
        // Path parts.
        $pathParts = Str::explode($path, $this->directorySeparator);

        // Path parts without base path.
        $pathParts = $pathParts->diff(
            explode($this->directorySeparator, $this->config->get('base_path'))
        );

        // Path parts (crumbs) without base path, cleaned up.
        $pathParts = $pathParts->filter(static function (string $crumb): bool {
            return ! in_array($crumb, [null, '.']);
        });

        // Mapping of crumbs to target paths.
        $pathParts = $pathParts->reduce(function (Collection $carry, string $crumb): Collection {
            return $carry->put($crumb,
                $carry->last() . $this->directorySeparator . rawurlencode($crumb)
            );
        }, new Collection);

        // Return mapping of crumbs to valid paths, but with target paths formatted.
        return $pathParts->map(static function (string $path): string {
            return sprintf('?dir=%s', $path);
        });
    }
}
