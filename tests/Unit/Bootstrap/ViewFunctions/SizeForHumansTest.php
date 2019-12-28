<?php

namespace Tests\Unit\Bootstrap\ViewFunctions;

use App\Bootstrap\ViewFunctions\SizeForHumans;
use PHLAK\Config\Config;
use PHPUnit\Framework\TestCase;

class SizeForHumansTest extends TestCase
{
    public function test_it_can_convert_bytes_to_bytes(): void
    {
        $sizeForHumans = new SizeForHumans($this->createMock(Config::class));

        $this->assertEquals('13.00B', $sizeForHumans(13));
    }

    public function test_it_can_convert_bytes_to_kibibytes(): void
    {
        $sizeForHumans = new SizeForHumans($this->createMock(Config::class));

        $this->assertEquals('13.37KB', $sizeForHumans(13690));
    }

    public function test_it_can_convert_bytes_to_mebibytes(): void
    {
        $sizeForHumans = new SizeForHumans($this->createMock(Config::class));

        $this->assertEquals('13.37MB', $sizeForHumans(14019461));
    }

    public function test_it_can_convert_bytes_to_gibibytes(): void
    {
        $sizeForHumans = new SizeForHumans($this->createMock(Config::class));

        $this->assertEquals('13.37GB', $sizeForHumans(14355900000));
    }

    public function test_it_can_convert_bytes_to_tebibytes(): void
    {
        $sizeForHumans = new SizeForHumans($this->createMock(Config::class));

        $this->assertEquals('13.37TB', $sizeForHumans(14700500000000));
    }

    public function test_it_can_convert_bytes_to_pebibytes(): void
    {
        $sizeForHumans = new SizeForHumans($this->createMock(Config::class));

        $this->assertEquals('13.37PB', $sizeForHumans(15053300000000000));
    }

    public function test_it_can_convert_bytes_to_exbibytes(): void
    {
        $sizeForHumans = new SizeForHumans($this->createMock(Config::class));

        $this->assertEquals('8.00EB', $sizeForHumans(PHP_INT_MAX));
    }
}
