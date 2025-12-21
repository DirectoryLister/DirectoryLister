<?php

declare(strict_types=1);

namespace App\Functions;

use League\CommonMark\ConverterInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Markup;

class Markdown extends ViewFunction
{
    public string $name = 'markdown';

    public function __construct(
        private ConverterInterface $converter,
        private CacheInterface $cache
    ) {}

    /** Parses a string of markdown into HTML. */
    public function __invoke(string $string): Markup
    {
        $markdown = $this->cache->get(
            sprintf('markdown-%s', sha1($string)),
            fn (): string => (string) $this->converter->convert($string)
        );

        return new Markup($markdown, 'UTF-8');
    }
}
