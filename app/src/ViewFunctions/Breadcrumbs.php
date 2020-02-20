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
            return $carry->put($crumb, ltrim("{$carry->last()}/{$crumb}", '/'));
        }, new Collection)->map(function (string $path): string {
            $relativeRoot = substr(getcwd(), strlen($_SERVER['DOCUMENT_ROOT']));
            return sprintf($this->config->get('app.rewrite', false) ? $relativeRoot . '/' . $path : '?dir=%s', $path);
        });
    }
}
