<?php

declare(strict_types=1);

namespace App\Actions;

use DI\Container;

class CanonicalPath
{
    public function __construct(
        private Container $container,
    ) {}

    /** Resolve a relative path to its canonical absolute path. */
    public function __invoke(string $path): ?string
    {
        $canonical = realpath($this->container->call('full_path', ['path' => $path]));

        return $canonical === false ? null : $canonical;
    }
}
