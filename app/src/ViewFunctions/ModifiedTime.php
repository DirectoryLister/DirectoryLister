<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use App\Config;
use DateInvalidTimeZoneException;
use DateTimeImmutable;
use DateTimeZone;
use RuntimeException;
use Symfony\Component\Finder\SplFileInfo;

class ModifiedTime extends ViewFunction
{
    protected string $name = 'modified_time';

    public function __construct(
        private Config $config
    ) {}

    /** Get the modified time from a file object. */
    public function __invoke(SplFileInfo $file): string
    {
        try {
            /** @throws RuntimeException */
            $modifiedTime = $file->getMTime();
        } catch (RuntimeException) {
            $modifiedTime = lstat($file->getPathname())['mtime'];
        }

        try {
            $timezone = new DateTimeZone($this->config->get('timezone'));
        } catch (DateInvalidTimeZoneException) {
            return '—';
        }

        $date = DateTimeImmutable::createFromTimestamp($modifiedTime)->setTimezone($timezone);

        return $date->format($this->config->get('date_format'));
    }
}
