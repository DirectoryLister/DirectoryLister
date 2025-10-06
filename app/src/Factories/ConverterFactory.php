<?php

declare(strict_types=1);

namespace App\Factories;

use League\CommonMark\ConverterInterface;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\DescriptionList\DescriptionListExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class ConverterFactory
{
    public function __invoke(): ConverterInterface
    {
        $environment = new Environment;

        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new DescriptionListExtension);

        return new MarkdownConverter($environment);
    }
}
