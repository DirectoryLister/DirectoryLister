<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use League\CommonMark\GithubFlavoredMarkdownConverter;
use Symfony\Contracts\Cache\CacheInterface;

class Markdown extends ViewFunction
{
    protected string $name = 'markdown';

    public function __construct(
        private GithubFlavoredMarkdownConverter $converter,
        private CacheInterface $cache
    ) {}

    /** Parses a string of markdown into HTML. */
    public function __invoke(string $string): string
    {
        return $this->cache->get(
            sprintf('markdown-%s', sha1($string)),
            fn (): string => (string) $this->converter->convert($string)
        );
    }
}
