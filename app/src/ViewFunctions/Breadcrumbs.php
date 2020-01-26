<?php

namespace App\ViewFunctions;

use Tightenco\Collect\Support\Collection;

class Breadcrumbs extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'breadcrumbs';

    /**
     * Build an array of breadcrumbs for a given path.
     *
     * @param string $path
     *
     * @return array
     */
    public function __invoke(string $path)
    {
        $breadcrumbs = Collection::make(explode('/', $path))->diff(
            explode('/', $this->container->get('base_path'))
        )->filter();

        return $breadcrumbs->filter(function (string $crumb) {
            return $crumb !== '.';
        })->reduce(function (Collection $carry, string $crumb) {
            return $carry->put($crumb, $carry->last() . '/' . $crumb);
        }, new Collection)->map(function (string $path) {
            return '/' . ltrim(dirname($_SERVER['SCRIPT_NAME']) . $path, '/');
        });
    }
}
