<?php

declare(strict_types=1);

namespace Tests\Functions;

use App\Functions\Icon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Finder\SplFileInfo;
use Tests\TestCase;

#[CoversClass(Icon::class)]
class IconTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->container->set('icons', ['php' => 'fab fa-php']);
    }

    #[Test]
    public function it_can_return_icon_markup_for_a_file(): void
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('isDir')->willReturn(false);
        $file->method('getExtension')->willReturn('php');

        $output = $this->container->call(Icon::class, ['file' => $file]);

        $this->assertEquals('<i class="fab fa-php fa-fw fa-lg"></i>', (string) $output);
    }

    #[Test]
    public function it_can_return_icon_markup_for_a_directory(): void
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('isDir')->willReturn(true);

        $output = $this->container->call(Icon::class, ['file' => $file]);

        $this->assertEquals('<i class="fas fa-folder fa-fw fa-lg"></i>', (string) $output);
    }

    #[Test]
    public function it_can_return_the_default_icon_markup(): void
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('isDir')->willReturn(false);
        $file->method('getExtension')->willReturn('default');

        $output = $this->container->call(Icon::class, ['file' => $file]);

        $this->assertEquals('<i class="fas fa-file fa-fw fa-lg"></i>', (string) $output);
    }
}
