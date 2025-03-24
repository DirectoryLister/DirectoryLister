<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use App\Config;
use DateInvalidTimeZoneException;
use DateTimeImmutable;
use DateTimeInterface;
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
            $appTimezone = new DateTimeZone($this->config->get('timezone'));
        } catch (DateInvalidTimeZoneException) {
            return '—';
        }

        $date = DateTimeImmutable::createFromFormat('U', (string) $modifiedTime);

        if (! $date instanceof DateTimeInterface) {
            return '—';
        }

        return $date->setTimezone($appTimezone)->format($this->config->get('date_format'));
    }
}
