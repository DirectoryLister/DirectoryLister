<?php

namespace App\ViewFunctions;

use App\Config;
use RuntimeException;
use Symfony\Component\Finder\SplFileInfo;

class ModifiedTime extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'modified_time';

    /** @var Config The application config */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /** Get the modified time from a file object. */
    public function __invoke(SplFileInfo $file): string
    {
        try {
            $modifiedTime = $file->getMTime();
        } catch (RuntimeException $exception) {
            $modifiedTime = lstat($file->getPathname())['mtime'];
        }

        return date($this->config->get('date_format'), $modifiedTime);
    }
}
