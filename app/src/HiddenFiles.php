<?php

declare(strict_types=1);

namespace App;

use BadMethodCallException;
use Illuminate\Support\Collection;

/** @extends Collection<int, string> */
class HiddenFiles extends Collection
{
    protected function __construct($items = [])
    {
        parent::__construct($items);
    }

    public static function make($items = []): never
    {
        throw new BadMethodCallException('Method not implemented');
    }

    /** Create a new HiddenFiles collection object. */
    public static function fromConfig(Config $config): self
    {
        $items = $config->get('hidden_files');

        if (is_readable($config->get('hidden_files_list'))) {
            $hiddenFiles = file($config->get('hidden_files_list'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $items = array_merge($items, $hiddenFiles ?: []);
        }

        if ($config->get('hide_app_files')) {
            $items = array_merge($items, $config->get('app_files'));
        }

        return new self(array_unique($items));
    }
}
