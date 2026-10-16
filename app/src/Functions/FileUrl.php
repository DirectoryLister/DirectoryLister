<?php

declare(strict_types=1);

namespace App\Functions;

use App\Actions\DirectoryUrl;
use App\Actions\IsDirectLink;
use DI\Container;

class FileUrl extends Url
{
    public string $name = 'file_url';

    public function __construct(
        Container $container,
        private IsDirectLink $isDirectLink,
        private DirectoryUrl $directoryUrl,
        string $directorySeparator = DIRECTORY_SEPARATOR,
    ) {
        parent::__construct($container, $directorySeparator);
    }

    public function __invoke(string $path = '/'): string
    {
        $normalizedPath = $this->normalizePath($path);

        if ($normalizedPath === '') {
            return '';
        }

        $fullPath = $this->container->call('full_path', ['path' => $normalizedPath]);
        $escapedPath = $this->escape($normalizedPath);

        if (is_file($fullPath)) {
            return ($this->isDirectLink)($fullPath) ? $escapedPath : sprintf('?file=%s', $escapedPath);
        }

        return ($this->directoryUrl)($normalizedPath, $this->directorySeparator);
    }
}
