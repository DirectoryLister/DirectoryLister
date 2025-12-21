<?php

declare(strict_types=1);

namespace App\Actions;

use App\HiddenFiles;
use DI\Attribute\Inject;
use DI\Container;
use PHLAK\Splat\Glob;
use PHLAK\Splat\Pattern;
use SplFileInfo;

class IsHidden
{
    /** Hidden files pattern cache */
    private Pattern $pattern;

    public function __construct(
        Container $container,
        #[Inject('files_path')] string $filesPath,
    ) {
        $this->pattern = Pattern::make(sprintf('%s{%s}', Pattern::escape(
            $filesPath . DIRECTORY_SEPARATOR
        ), HiddenFiles::fromContainer($container)->implode(',')));
    }

    /** Determine if a file is hidden. */
    public function file(SplFileInfo $file): bool
    {
        return Glob::match($this->pattern, (string) $file->getRealPath());
    }

    /** Determine if a file path is hidden. */
    public function path(string $path): bool
    {
        return Glob::match($this->pattern, $path);
    }
}
