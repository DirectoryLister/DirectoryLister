<?php

namespace App\ViewFunctions;

use App\Config;

class Analytics extends ViewFunction
{
    protected string $name = 'analytics';

    public function __construct(
        private Config $config
    ) {}

    /** Get the contents of the .analytics file. */
    public function __invoke(): string
    {
        $analyticsFile = $this->config->get('base_path') . '/' . $this->config->get('analytics_file');

        if (! is_file($analyticsFile)) {
            return '';
        }

        return trim((string) file_get_contents($analyticsFile));
    }
}
