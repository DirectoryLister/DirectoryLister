<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\SizeForHumans;
use RuntimeException;
use Symfony\Component\Finder\SplFileInfo;
use Tests\TestCase;

/** @covers \App\ViewFunctions\SizeForHumans */
class SizeForHumansTest extends TestCase
{
    public function test_it_can_convert_bytes_to_bytes(): void
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('getSize')->willReturn(13);

        $sizeForHumans = new SizeForHumans;

        $this->assertEquals('13.00B', $sizeForHumans($file));
    }

    public function test_it_can_convert_bytes_to_kibibytes(): void
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('getSize')->willReturn(13690);

        $sizeForHumans = new SizeForHumans;

        $this->assertEquals('13.37KB', $sizeForHumans($file));
    }

    public function test_it_can_convert_bytes_to_mebibytes(): void
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('getSize')->willReturn(14019461);

        $sizeForHumans = new SizeForHumans;

        $this->assertEquals('13.37MB', $sizeForHumans($file));
    }

    public function test_it_can_convert_bytes_to_gibibytes(): void
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('getSize')->willReturn(14355900000);

        $sizeForHumans = new SizeForHumans;

        $this->assertEquals('13.37GB', $sizeForHumans($file));
    }

    public function test_it_can_convert_bytes_to_tebibytes(): void
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('getSize')->willReturn(14700500000000);

        $sizeForHumans = new SizeForHumans;

        $this->assertEquals('13.37TB', $sizeForHumans($file));
    }

    public function test_it_can_convert_bytes_to_pebibytes(): void
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('getSize')->willReturn(15053300000000000);

        $sizeForHumans = new SizeForHumans;

        $this->assertEquals('13.37PB', $sizeForHumans($file));
    }

    public function test_it_can_convert_bytes_to_exbibytes(): void
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('getSize')->willReturn(PHP_INT_MAX);

        $sizeForHumans = new SizeForHumans;

        $this->assertEquals('8.00EB', $sizeForHumans($file));
    }

    /** @test */
    public function it_returns_zero_bytes_when_it_can_not_get_the_file_size(): void
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('getSize')->willThrowException(new RuntimeException);

        $sizeForHumans = new SizeForHumans;

        $this->assertEquals('0B', $sizeForHumans($file));
    }
}
