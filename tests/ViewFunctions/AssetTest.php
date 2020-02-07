<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Asset;
use Tests\TestCase;

class AssetTest extends TestCase
{
    public function test_it_can_return_an_asset_path(): void
    {
        $this->container->set('base_path', $this->filePath('.'));
        $asset = new Asset($this->container, $this->config);

        $this->assertEquals('app/assets/app.css?id=417c7a9bc03852aafb27', $asset('app.css'));
        $this->assertEquals('app/assets/app.js?id=6753a7269276c7b52692', $asset('app.js'));
        $this->assertEquals('app/assets/images/icon.png', $asset('images/icon.png'));
    }

    public function test_it_can_return_an_asset_with_a_subdirectory(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/some/dir/index.php';

        $asset = new Asset($this->container, $this->config);

        $this->assertEquals('app/assets/app.css?id=417c7a9bc03852aafb27', $asset('app.css'));
        $this->assertEquals('app/assets/app.js?id=6753a7269276c7b52692', $asset('app.js'));
        $this->assertEquals('app/assets/images/icon.png', $asset('images/icon.png'));
    }
}
