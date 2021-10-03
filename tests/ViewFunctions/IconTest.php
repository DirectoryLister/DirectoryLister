<?php

namespace Tests\ViewFunctions;

use App\Config;
use App\ViewFunctions\Icon;
use Symfony\Component\Finder\SplFileInfo;
use Tests\TestCase;

/** @covers \App\ViewFunctions\Icon */
class IconTest extends TestCase
{
    /** @var Config Application config */
    protected $config;

    public function setUp(): void
    {
        parent::setUp();

        $this->container->set('icons', ['php' => 'fab fa-php']);
    }

    public function test_it_can_return_icon_markup_for_a_file(): void
    {
        $icon = new Icon($this->config);
        $file = $this->createMock(SplFileInfo::class);
        $file->method('isDir')->willReturn(false);
        $file->method('getExtension')->willReturn('php');

        $this->assertEquals('<i class="fab fa-php fa-fw fa-lg"></i>', $icon($file));
    }

    public function test_it_can_return_icon_markup_for_a_directory(): void
    {
        $icon = new Icon($this->config);
        $file = $this->createMock(SplFileInfo::class);
        $file->method('isDir')->willReturn(true);

        $this->assertEquals('<i class="fas fa-folder fa-fw fa-lg"></i>', $icon($file));
    }

    public function test_it_can_return_the_default_icon_markup(): void
    {
        $icon = new Icon($this->config);
        $file = $this->createMock(SplFileInfo::class);
        $file->method('isDir')->willReturn(false);
        $file->method('getExtension')->willReturn('default');

        $this->assertEquals('<i class="fas fa-file fa-fw fa-lg"></i>', $icon($file));
    }
}
