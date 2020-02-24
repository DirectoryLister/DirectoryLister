<?php

namespace Tests\Providers;

use App\Providers\TranslationProvider;
use RuntimeException;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Contracts\Translation\TranslatorInterface;
use Tests\TestCase;

class TranslationProviderTest extends TestCase
{
    public function test_it_registers_the_translation_component(): void
    {
        (new TranslationProvider($this->container, $this->config))();

        $translator = $this->container->get(TranslatorInterface::class);

        $this->assertEquals('en', $translator->getLocale());
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('en'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('fr'));
    }

    public function test_it_throws_an_exception_for_an_invalid_language(): void
    {
        $this->expectException(RuntimeException::class);

        $this->config->set('app.language', 'xx');
        (new TranslationProvider($this->container, $this->config))();
    }
}
