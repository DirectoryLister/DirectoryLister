<?php

namespace App\ViewFunctions;

use App\Config;
use App\Support\Str;
use Tightenco\Collect\Support\Collection;

class Breadcrumbs extends ViewFunction
{
    protected string $name = 'breadcrumbs';

    /** Create a new Breadcrumbs object. */
    public function __construct(
        private Config $config,
        private string $directorySeparator = DIRECTORY_SEPARATOR
    ) {}

    /**
     * Build a collection of breadcrumbs for a given path.
     *
     * @return Collection<int, string>
     */
    public function __invoke(string $path): Collection
    {
        return Str::explode($path, $this->directorySeparator)->diffAssoc(
            explode($this->directorySeparator, $this->config->get('base_path'))
        )->filter(static function (string $crumb): bool {
            return ! in_array($crumb, [null, '.']);
        })->reduce(function (Collection $carry, string $crumb): Collection {
            return $carry->put($crumb, ltrim(
                $carry->last() . $this->directorySeparator . rawurlencode($crumb), $this->directorySeparator
            ));
        }, new Collection)->map(static function (string $path): string {
            return sprintf('?dir=%s', $path);
        });
    }
}
