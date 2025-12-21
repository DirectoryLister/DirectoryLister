<?php

declare(strict_types=1);

namespace App\Factories;

use App\Exceptions\InvalidConfiguration;
use DI\Attribute\Inject;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslationFactory
{
    #[Inject('language')]
    private string $language;

    #[Inject('translations_path')]
    private string $translationsPath;

    public function __construct(
        private CacheInterface $cache
    ) {}

    public function __invoke(): TranslatorInterface
    {
        if (! in_array($this->language, $translations = $this->translations())) {
            throw InvalidConfiguration::forOption('language', $this->language);
        }

        $translator = new Translator($this->language);
        $translator->addLoader('yaml', new YamlFileLoader);

        foreach ($translations as $this->language) {
            $translator->addResource('yaml', sprintf(
                '%s/%s.yaml', $this->translationsPath, $this->language
            ), $this->language);
        }

        return $translator;
    }

    /**
     * Get an array of available translation languages.
     *
     * @return list<string>
     */
    private function translations(): array
    {
        return $this->cache->get('translations', fn (): array => array_values(array_map(
            static fn (SplFileInfo $file): string => $file->getBasename('.yaml'),
            iterator_to_array(Finder::create()->in($this->translationsPath)->name('*.yaml'))
        )));
    }
}
