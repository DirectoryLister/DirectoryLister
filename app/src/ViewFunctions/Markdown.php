<?php

namespace App\ViewFunctions;

use ParsedownExtra;
use Symfony\Contracts\Cache\CacheInterface;

class Markdown extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'markdown';

    /** @var ParsedownExtra The markdown parser */
    protected $parser;

    /** @var CacheInterface */
    protected $cache;

    public function __construct(ParsedownExtra $parser, CacheInterface $cache)
    {
        $this->parser = $parser;
        $this->cache = $cache;
    }

    /** Parses a string of markdown into HTML. */
    public function __invoke(string $string): string
    {
        return $this->cache->get(
            sprintf('markdown-%s', sha1($string)),
            function () use ($string): string {
                return $this->parser->parse($string);
            }
        );
    }
}
