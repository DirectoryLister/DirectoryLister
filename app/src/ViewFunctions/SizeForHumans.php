<?php

namespace App\ViewFunctions;

use RuntimeException;
use Symfony\Component\Finder\SplFileInfo;

class SizeForHumans extends ViewFunction
{
    protected string $name = 'size_for_humans';

    /** Get the human readable file size from a file object. */
    public function __invoke(SplFileInfo $file): string
    {
        try {
            $fileSize = $file->getSize();
        } catch (RuntimeException $exception) {
            return '0B';
        }

        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = (int) floor((strlen((string) $fileSize) - 1) / 3);

        return sprintf('%.2f%s', $fileSize / pow(1024, $factor), $sizes[$factor]);
    }
}
