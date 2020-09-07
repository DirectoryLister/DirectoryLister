<?php

namespace App\ViewFunctions;

use Symfony\Component\Finder\SplFileInfo;

class SizeForHumans extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'size_for_humans';

    /** Get the human readable file size from a file object. */
    public function __invoke(SplFileInfo $file): string
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = (int) floor((strlen((string) $file->getSize()) - 1) / 3);

        return sprintf('%.2f', $file->getSize() / pow(1024, $factor)) . $sizes[$factor];
    }
}
