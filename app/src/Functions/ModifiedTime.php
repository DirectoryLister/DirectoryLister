<?php

declare(strict_types=1);

namespace App\Functions;

use DateInvalidTimeZoneException;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use DI\Attribute\Inject;
use RuntimeException;
use Symfony\Component\Finder\SplFileInfo;

class ModifiedTime extends ViewFunction
{
    public string $name = 'modified_time';

    #[Inject('timezone')]
    private string $timezone;

    #[Inject('date_format')]
    private string $dateFormat;

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
            $appTimezone = new DateTimeZone($this->timezone);
        } catch (DateInvalidTimeZoneException) {
            return '—';
        }

        $date = DateTimeImmutable::createFromFormat('U', (string) $modifiedTime);

        if (! $date instanceof DateTimeInterface) {
            return '—';
        }

        return $date->setTimezone($appTimezone)->format($this->dateFormat);
    }
}
