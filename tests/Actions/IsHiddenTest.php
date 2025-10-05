<?php

declare(strict_types=1);

namespace Test\Actions;

use App\Actions\IsHidden;
use App\HiddenFiles;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SplFileInfo;
use Tests\TestCase;

#[CoversClass(IsHidden::class)]
class IsHiddenTest extends TestCase
{
    private IsHidden $isHidden;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container->set('hidden_files', ['**.txt']);

        $this->isHidden = new IsHidden($this->config, HiddenFiles::fromConfig($this->config));
    }

    #[Test]
    public function it_can_determine_if_a_file_is_hidden(): void
    {
        $hiddenFile = new SplFileInfo($this->filePath('golf.txt'));
        $visibleFile = new SplFileInfo($this->filePath('hotel.md'));

        $this->assertTrue($this->isHidden->file($hiddenFile));
        $this->assertFalse($this->isHidden->file($visibleFile));
    }

    #[Test]
    public function it_can_determine_if_a_path_is_hidden(): void
    {
        $hiddenPath = $this->filePath('golf.txt');
        $visiblePath = $this->filePath('hotel.md');

        $this->assertTrue($this->isHidden->path($hiddenPath));
        $this->assertFalse($this->isHidden->path($visiblePath));
    }
}
