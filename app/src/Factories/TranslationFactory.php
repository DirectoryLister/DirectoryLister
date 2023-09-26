<?php

namespace App\Factories;

use App\Config;
use App\Exceptions\InvalidConfiguration;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslationFactory
{
    /** Create a new TranslationFactory object. */
    public function __construct(
        private Config $config,
        private CacheInterface $cache
    ) {}

    /** Initialize and return the translation component. */
    public function __invoke(): TranslatorInterface
    {
        if (! in_array(
            $language = $this->config->get('language'),
            $translations = $this->translations())
        ) {
            throw InvalidConfiguration::fromConfig('language', $language);
        }

        $translator = new Translator($language);
        $translator->addLoader('yaml', new YamlFileLoader);

        foreach ($translations as $language) {
            $translator->addResource('yaml', sprintf(
                '%s/%s.yaml', $this->config->get('translations_path'), $language
            ), $language);
        }

        return $translator;
    }

    /** Get an array of available translation languages. */
    protected function translations(): array
    {
        return $this->cache->get('translations', function (): array {
            return array_values(array_map(function (SplFileInfo $file): string {
                return $file->getBasename('.yaml');
            }, iterator_to_array(
                Finder::create()->in($this->config->get('translations_path'))->name('*.yaml')
            )));
        });
    }
}
