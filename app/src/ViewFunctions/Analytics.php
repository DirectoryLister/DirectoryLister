<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use App\Config;
use Twig\Markup;

class Analytics extends ViewFunction
{
    protected string $name = 'analytics';

    public function __construct(
        private Config $config
    ) {}

    /** Get the contents of the .analytics file. */
    public function __invoke(): Markup
    {
        $analyticsFile = $this->config->get('base_path') . '/' . $this->config->get('analytics_file');

        if (! is_file($analyticsFile)) {
            return '';
        }

        $analytics = trim((string) file_get_contents($analyticsFile));

        return new Markup($analytics, 'UTF-8');
    }
}
