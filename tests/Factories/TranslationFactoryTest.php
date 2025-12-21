<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Exceptions\InvalidConfiguration;
use App\Factories\TranslationFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Translator;
use Tests\TestCase;

#[CoversClass(TranslationFactory::class)]
class TranslationFactoryTest extends TestCase
{
    #[Test]
    public function it_registers_the_translation_component(): void
    {
        /** @var Translator $translator */
        $translator = $this->container->call(TranslationFactory::class);

        $this->assertEquals('en', $translator->getLocale());
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('ar'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('de'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('en'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('es'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('et'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('fr'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('hr'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('hu'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('id'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('it'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('kr'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('nl'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('pl'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('fa'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('pt-BR'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('ro'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('ru'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('sv'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('tr'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('zh-CN'));
        $this->assertInstanceOf(MessageCatalogue::class, $translator->getCatalogue('zh-TW'));
    }

    #[Test]
    public function it_throws_an_exception_for_an_invalid_language(): void
    {
        $this->expectException(InvalidConfiguration::class);

        $this->container->set('language', 'xx');

        $this->container->call(TranslationFactory::class);
    }
}
