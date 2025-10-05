<?php

declare(strict_types=1);

namespace App\Actions;

use App\Config;
use App\HiddenFiles;
use PHLAK\Splat\Glob;
use PHLAK\Splat\Pattern;
use SplFileInfo;

class IsHidden
{
    /** Hidden files pattern cache */
    private Pattern $pattern;

    public function __construct(
        private Config $config,
        private HiddenFiles $hiddenFiles,
    ) {
        $this->pattern = Pattern::make(sprintf('%s{%s}', Pattern::escape(
            $this->config->get('files_path') . DIRECTORY_SEPARATOR
        ), $this->hiddenFiles->implode(',')));
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
