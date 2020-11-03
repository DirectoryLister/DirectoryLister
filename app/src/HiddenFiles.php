<?php

namespace App;

use BadMethodCallException;
use Tightenco\Collect\Support\Collection;

class HiddenFiles extends Collection
{
    /** {@inheritdoc} */
    protected function __construct(...$args)
    {
        parent::__construct(...$args);
    }

    /** {@inheritdoc} */
    public static function make($items = [])
    {
        throw new BadMethodCallException('Method not implemented');
    }

    /** Create a new HiddenFiles collection object. */
    public static function fromConfig(Config $config): self
    {
        $items = $config->get('hidden_files');

        if (is_readable($config->get('hidden_files_list'))) {
            $items = array_merge($items, file(
                $config->get('hidden_files_list'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
            ));
        }

        if ($config->get('hide_app_files')) {
            $items = array_merge($items, $config->get('app_files'));
        }

        return new self(array_unique($items));
    }
}
