<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\ModifiedTime;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Finder\SplFileInfo;
use Tests\TestCase;

#[CoversClass(ModifiedTime::class)]
class ModifiedTimeTest extends TestCase
{
    #[Test]
    public function it_can_return_the_modified_time_for_a_file(): void
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('getMTime')->willReturn(516976496);

        $modifiedTime = new ModifiedTime($this->config);

        $this->assertEquals('1986-05-20 12:34:56', $modifiedTime($file));
    }
}
