<?php

namespace App\Providers;

use DI\Container;
use Illuminate\Support\Collection;
use PHLAK\Config\Config;
use RuntimeException;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslationProvider
{
    /** @const Available translation languages */
    protected const LANGUAGES = [
        'de', 'en', 'es', 'fr', 'it', 'ro', 'ru', 'zh-CN', 'zh-TW'
    ];

    /** @var Container The applicaiton container */
    protected $container;

    /** @var Config The application config */
    protected $config;

    /**
     * Create a new TranslationProvider object.
     *
     * @param \DI\Container        $container
     * @param \PHLAK\Config\Config $config
     */
    public function __construct(Container $container, Config $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * Initialize and register the translation component.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $language = $this->config->get('app.language', 'en');

        if (! in_array($language, self::LANGUAGES)) {
            throw new RuntimeException("Invalid language option '{$language}'");
        }

        $translator = new Translator($language);
        $translator->addLoader('yaml', new YamlFileLoader());

        Collection::make(self::LANGUAGES)->each(
            function (string $language) use ($translator): void {
                $resource = sprintf('app/translations/%s.yaml', $language);
                $translator->addResource('yaml', $resource, $language);
            }
        );

        $this->container->set(TranslatorInterface::class, $translator);
    }
}
