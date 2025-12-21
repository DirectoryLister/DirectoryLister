<?php

declare(strict_types=1);

namespace Tests\Functions;

use App\Functions\Vite;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Vite::class)]
class ViteTest extends TestCase
{
    #[Test]
    public function it_can_get_tags_for_a_list_of_assets_when_in_dev_mode(): void
    {
        $this->container->set('asset_path', $this->filePath('.'));
        $this->container->set('manifest_path', $this->filePath('./manifest.json'));

        $tags = $this->container->call(Vite::class, [
            'assets' => ['app/resources/js/app.js', 'app/resources/css/app.css'],
        ]);

        $this->assertSame(<<<HTML
        <script type="module" src="http://localhost:5173/@vite/client"></script>
        <script type="module" src="http://localhost:5173/app/resources/js/app.js"></script>
        <link rel="stylesheet" href="http://localhost:5173/app/resources/css/app.css">
        HTML, (string) $tags);
    }

    #[Test]
    public function it_can_get_tags_for_a_list_of_assets_when_in_build_mode(): void
    {
        $this->container->set('assets_path', $this->filePath('app/assets'));
        $this->container->set('manifest_path', $this->filePath('app/assets/manifest.json'));

        $tags = $this->container->call(Vite::class, [
            'assets' => ['app/resources/js/app.js', 'app/resources/css/app.css'],
        ]);

        $this->assertSame(<<<HTML
        <script type="module" src="app/assets/app-DwN9UvfZ.js"></script>
        <link rel="stylesheet" href="app/assets/app-DcdLJcW2.css">
        HTML, (string) $tags);
    }
}
