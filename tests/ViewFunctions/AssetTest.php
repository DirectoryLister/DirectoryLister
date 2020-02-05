<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Asset;
use Tests\TestCase;

class AssetTest extends TestCase
{
    public function test_it_can_return_an_asset_path(): void
    {
        $asset = new Asset($this->container, $this->config);

        $this->assertEquals('app/assets/css/app.css', $asset('css/app.css'));
        $this->assertEquals('app/assets/js/app.js', $asset('js/app.js'));
    }

    public function test_it_can_return_an_asset_with_a_subdirectory(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/some/dir/index.php';

        $asset = new Asset($this->container, $this->config);

        $this->assertEquals('app/assets/css/app.css', $asset('css/app.css'));
        $this->assertEquals('app/assets/js/app.js', $asset('js/app.js'));
    }
}
