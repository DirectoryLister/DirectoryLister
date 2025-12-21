<?php

declare(strict_types=1);

namespace App\Filters;

use DI\Attribute\Inject;
use League\CommonMark\ConverterInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Markup;

class Markdown extends ViewFilter
{
    public string $name = 'markdown';

    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    #[Inject(ConverterInterface::class)]
    private ConverterInterface $converter;

    public function __invoke(string $string): Markup
    {
        $markdown = $this->cache->get(
            sprintf('markdown-%s', sha1($string)),
            fn (): string => (string) $this->converter->convert($string)
        );

        return new Markup($markdown, 'UTF-8');
    }
}
