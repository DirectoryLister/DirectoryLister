<?php

namespace Tests\Factories;

use App\Factories\TranslationFactory;
use RuntimeException;
use Symfony\Component\Translation\MessageCatalogue;
use Tests\TestCase;

class TranslationFactoryTest extends TestCase
{
    public function test_it_registers_the_translation_component(): void
    {
        $translator = (new TranslationFactory($this->container))();

        $this->assertEquals('en', $translator->getLocale());
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('en'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('fr'));
    }

    public function test_it_throws_an_exception_for_an_invalid_language(): void
    {
        $this->expectException(RuntimeException::class);

        $this->container->set('language', 'xx');
        (new TranslationFactory($this->container))();
    }
}
