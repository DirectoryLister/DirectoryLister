<?php

declare(strict_types=1);

namespace App\Functions;

use App\Support\Str;
use DI\Attribute\Inject;
use Illuminate\Support\Collection;

class Breadcrumbs extends ViewFunction
{
    public string $name = 'breadcrumbs';

    #[Inject('files_path')]
    private string $filesPath;

    /** @param non-empty-string $directorySeparator */
    public function __construct(
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
            explode($this->directorySeparator, $this->filesPath)
        )->filter(
            static fn (string $crumb): bool => ! in_array($crumb, [null, '.'])
        )->reduce(fn (Collection $carry, string $crumb): Collection => $carry->put($crumb, ltrim(
            $carry->last() . $this->directorySeparator . rawurlencode($crumb), $this->directorySeparator
        )), new Collection)->map(
            static fn (string $path): string => sprintf('?dir=%s', $path)
        );
    }
}
