<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use RuntimeException;
use Symfony\Component\Finder\SplFileInfo;

class SizeForHumans extends ViewFunction
{
    private const UNITS = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    protected string $name = 'size_for_humans';

    /** Get the human readable file size from a file object. */
    public function __invoke(SplFileInfo $file): string
    {
        try {
            $fileSize = $file->getSize();
        } catch (RuntimeException) {
            return '0B';
        }

        $factor = (int) floor((strlen((string) $fileSize) - 1) / 3);

        return sprintf('%.2f%s', $fileSize / pow(1024, $factor), self::UNITS[$factor]);
    }
}
