<?php

declare(strict_types=1);

namespace Tests\Actions;

use App\Actions\CanonicalPath;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(CanonicalPath::class)]
class CanonicalPathTest extends TestCase
{
    #[Test]
    public function it_resolves_a_path_to_its_canonical_absolute_path(): void
    {
        $canonicalPath = $this->container->get(CanonicalPath::class);

        $this->assertEquals($this->filePath('subdir'), $canonicalPath('subdir'));
        $this->assertEquals($this->filePath('golf.txt'), $canonicalPath('golf.txt'));
        $this->assertEquals($this->testFilesPath, $canonicalPath('.'));
    }

    #[Test]
    public function it_resolves_non_canonical_path_segments(): void
    {
        $canonicalPath = $this->container->get(CanonicalPath::class);

        $this->assertEquals($this->filePath('subdir'), $canonicalPath('./subdir'));
        $this->assertEquals($this->filePath('subdir'), $canonicalPath('subdir/'));
        $this->assertEquals($this->filePath('subdir'), $canonicalPath('subdir//'));
        $this->assertEquals($this->filePath('subdir'), $canonicalPath('subdir/.'));
        $this->assertEquals($this->filePath('subdir'), $canonicalPath('subdir/../subdir'));
        $this->assertEquals($this->filePath('golf.txt'), $canonicalPath('subdir/../golf.txt'));
    }

    #[Test]
    public function it_returns_null_for_a_non_existent_path(): void
    {
        $canonicalPath = $this->container->get(CanonicalPath::class);

        $this->assertNull($canonicalPath('404'));
    }
}
