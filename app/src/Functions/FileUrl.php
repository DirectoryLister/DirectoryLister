<?php

declare(strict_types=1);

namespace App\Functions;

use DI\Attribute\Inject;
use PHLAK\Splat\Glob;
use PHLAK\Splat\Pattern;

class FileUrl extends Url
{
    public string $name = 'file_url';

    /** Direct links pattern cache. */
    private ?Pattern $pattern = null;

    #[Inject('base_path')]
    private string $basePath;

    #[Inject('files_path')]
    private string $filesPath;

    #[Inject('direct_links')]
    private string|null $directLinks;

    public function __invoke(string $path = '/'): string
    {
        $normalizedPath = $this->normalizePath($path);

        if ($normalizedPath === '') {
            return '';
        }

        $fullPath = $this->container->call('full_path', ['path' => $normalizedPath]);
        $escapedPath = $this->escape($normalizedPath);

        if (is_file($fullPath)) {
            return $this->isDirectLink($fullPath) ? $escapedPath : sprintf('?file=%s', $escapedPath);
        }

        return sprintf('?dir=%s', $escapedPath);
    }

    /** Determine if a file should be directly linked. */
    private function isDirectLink(string $path): bool
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

        return Glob::matchStart($this->pattern, $path);
    }
}
