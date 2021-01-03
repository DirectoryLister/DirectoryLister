<?php

namespace App\Support;

use SplFileInfo;

class Utils
{
    /** Get the human readable file size from a file object. */
    public static function sizeForHumans(SplFileInfo $file): string
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = (int) floor((strlen((string) $file->getSize()) - 1) / 3);

        return sprintf('%.2f', $file->getSize() / pow(1024, $factor)) . $sizes[$factor];
    }
}
