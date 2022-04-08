<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\ModifiedTime;
use Symfony\Component\Finder\SplFileInfo;
use Tests\TestCase;

/** @covers \App\ViewFunctions\ModifiedTime */
class ModifiedTimeTest extends TestCase
{
    public function test_it_can_return_the_modified_time_for_a_file(): void
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('getMTime')->willReturn(516976496);

        $modifiedTime = new ModifiedTime($this->config);

        $this->assertEquals('1986-05-20 12:34:56', $modifiedTime($file));
    }
}
