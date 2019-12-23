<?php

namespace App\Bootstrap\ViewFunctions;

class SizeForHumans extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'sizeForHumans';

    /**
     * Convert file size from bytes to a readable size for humans.
     *
     * @param int $bytes File size in bytes
     *
     * @return string
     */
    public function __invoke(int $bytes): string
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = (int) floor((strlen((string) $bytes) - 1) / 3);

        return sprintf('%.2f', $bytes / pow(1024, $factor)) . $sizes[$factor];
    }
}
