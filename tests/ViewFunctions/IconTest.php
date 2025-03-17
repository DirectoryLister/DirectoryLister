<?php

declare(strict_types=1);

namespace Tests\ViewFunctions;

use App\Config;
use App\ViewFunctions\Icon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Finder\SplFileInfo;
use Tests\TestCase;

#[CoversClass(Icon::class)]
class IconTest extends TestCase
{
    /** @var Config Application config */
    protected $config;

    public function setUp(): void
    {
        parent::setUp();

        $this->container->set('icons', ['php' => 'fab fa-php']);
    }

    #[Test]
    public function it_can_return_icon_markup_for_a_file(): void
    {
        $icon = new Icon($this->config);
        $file = $this->createMock(SplFileInfo::class);
        $file->method('isDir')->willReturn(false);
        $file->method('getExtension')->willReturn('php');

        $this->assertEquals('<i class="fab fa-php fa-fw fa-lg"></i>', $icon($file));
    }

    #[Test]
    public function it_can_return_icon_markup_for_a_directory(): void
    {
        $icon = new Icon($this->config);
        $file = $this->createMock(SplFileInfo::class);
        $file->method('isDir')->willReturn(true);

        $this->assertEquals('<i class="fas fa-folder fa-fw fa-lg"></i>', $icon($file));
    }

    #[Test]
    public function it_can_return_the_default_icon_markup(): void
    {
        $icon = new Icon($this->config);
        $file = $this->createMock(SplFileInfo::class);
        $file->method('isDir')->willReturn(false);
        $file->method('getExtension')->willReturn('default');

        $this->assertEquals('<i class="fas fa-file fa-fw fa-lg"></i>', $icon($file));
    }
}
