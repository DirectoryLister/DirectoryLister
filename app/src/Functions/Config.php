<?php

declare(strict_types=1);

namespace App\Functions;

use DI\Attribute\Inject;
use DI\Container;
use DI\NotFoundException;

class Config extends ViewFunction
{
    public string $name = 'config';

    #[Inject(Container::class)]
    private Container $container;

    public function __invoke(string $key, mixed $default = null): mixed
    {
        try {
            $value = $this->container->get($key);
        } catch (NotFoundException) {
            return $default;
        }

        if (! is_string($value)) {
            return $value;
        }

        return match (strtolower($value)) {
            'true' => true,
            'false' => false,
            'null' => null,
            default => $value
        };
    }
}
