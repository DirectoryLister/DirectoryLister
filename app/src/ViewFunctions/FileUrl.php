<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use PHLAK\Splat\Glob;
use PHLAK\Splat\Pattern;

class FileUrl extends Url
{
    protected string $name = 'file_url';

    /** Direct links pattern cache. */
    private ?Pattern $pattern = null;

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
        if ($this->config->get('base_path') !== $this->config->get('files_path')) {
            return false;
        }

        if ($this->config->get('direct_links') === null) {
            return false;
        }

        if (! $this->pattern instanceof Pattern) {
            $this->pattern = Pattern::make(sprintf('%s{%s}', Pattern::escape(
                $this->config->get('files_path') . DIRECTORY_SEPARATOR
            ), $this->config->get('direct_links')));
        }

        return Glob::matchStart($this->pattern, $path);
    }
}
