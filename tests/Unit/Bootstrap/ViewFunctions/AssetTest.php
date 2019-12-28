<?php

namespace Tests\Unit\Bootstrap\ViewFunctions;

use App\Bootstrap\ViewFunctions\Asset;
use PHLAK\Config\Config;
use PHPUnit\Framework\TestCase;

class AssetTest extends TestCase
{
    public function test_it_can_return_an_asset_path(): void
    {
        $asset = new Asset($this->createMock(Config::class));

        $this->assertEquals('/app/dist/test.css', $asset('test.css'));
    }
}
