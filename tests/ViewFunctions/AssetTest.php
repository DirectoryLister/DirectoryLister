<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Asset;
use Tests\TestCase;

class AssetTest extends TestCase
{
    public function test_it_can_return_an_asset_path(): void
    {
        $asset = new Asset($this->container, $this->config);

        $this->assertEquals('/app/dist/css/app.css', $asset('css/app.css'));
        $this->assertEquals('/app/dist/js/app.js', $asset('js/app.js'));
    }
}
