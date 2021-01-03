<?php

namespace App\ViewFunctions;

use Symfony\Component\Finder\SplFileInfo;
use App\Support\Utils;

class SizeForHumans extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'size_for_humans';

    /** Get the human readable file size from a file object. */
    public function __invoke(SplFileInfo $file): string
    {
        return Utils::sizeForHumans($file);
    }
}
