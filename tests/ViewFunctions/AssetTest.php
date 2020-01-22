<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Asset;
use Tests\TestCase;

class AssetTest extends TestCase
{
    public function test_it_can_return_an_asset_path(): void
    {
        $asset = new Asset($this->container, $this->config);

        $this->assertEquals('/app/dist/test.css', $asset('test.css'));
        $this->assertEquals(
            '/app/dist/app.css?id=417c7a9bc03852aafb27',
            $asset('app.css')
        );
    }

    public function test_it_can_return_an_asset_path_without_a_mix_manifest(): void
    {
        $this->container->set('base_path', $this->filePath('subdir'));
        $asset = new Asset($this->container, $this->config);

        $this->assertEquals('/app/dist/test.css', $asset('test.css'));
        $this->assertEquals('/app/dist/app.css', $asset('app.css'));
    }
}
