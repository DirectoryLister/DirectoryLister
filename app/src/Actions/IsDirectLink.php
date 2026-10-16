<?php

declare(strict_types=1);

namespace App\Actions;

use DI\Attribute\Inject;
use PHLAK\Splat\Glob;
use PHLAK\Splat\Pattern;

class IsDirectLink
{
    /** Direct links pattern cache. */
    private ?Pattern $pattern = null;

    #[Inject('base_path')]
    private string $basePath;

    #[Inject('files_path')]
    private string $filesPath;

    #[Inject('direct_links')]
    private string|null $directLinks;

    /** Determine if a path is a direct link. */
    public function __invoke(string $path): bool
    {
        if ($this->basePath !== $this->filesPath) {
            return false;
        }

        if ($this->directLinks === null) {
            return false;
        }

        if (! $this->pattern instanceof Pattern) {
            $this->pattern = Pattern::make(sprintf('%s{%s}', Pattern::escape(
                $this->filesPath . DIRECTORY_SEPARATOR
            ), $this->directLinks));
        }

        return Glob::matchStart($this->pattern, $path . DIRECTORY_SEPARATOR);
    }
}
