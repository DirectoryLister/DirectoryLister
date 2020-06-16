<?php

namespace App;

use DI\Container;
use Tightenco\Collect\Support\Collection;

class HiddenFiles extends Collection
{
    /** {@inheritdoc} */
    protected function __construct($items = [])
    {
        $this->items = $this->getArrayableItems($items);
    }

    /**
     * Create a new HiddenFiles collection object.
     *
     * @param \DI\Container $container
     */
    public static function fromContainer(Container $container): self
    {
        $items = $container->get('hidden_files');

        if (is_readable($container->get('hidden_files_list'))) {
            $items = array_merge($items, file(
                $container->get('hidden_files_list'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
            ));
        }

        if ($container->get('hide_app_files')) {
            $items = array_merge($items, $container->get('app_files'));
        }

        return new static(array_unique($items));
    }
}
