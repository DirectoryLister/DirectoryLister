<?php

namespace Tests;

use App\HiddenFiles;
use BadMethodCallException;
use Tightenco\Collect\Support\Collection;

/** @covers \App\HiddenFiles */
class HiddenFilesTest extends TestCase
{
    /** @dataProvider hiddenFilesProvider */
    public function test_it_creates_a_collection_of_hidden_files(
        array $hiddenFilesArray,
        string $hiddenFilesList,
        bool $hideAppFiles,
        array $expected
    ): void {
        $this->container->set('hidden_files', $hiddenFilesArray);
        $this->container->set('hidden_files_list', $hiddenFilesList);
        $this->container->set('hide_app_files', $hideAppFiles);

        $hiddenFiles = HiddenFiles::fromConfig($this->config);

        $this->assertInstanceOf(Collection::class, $hiddenFiles);
        $this->assertEquals($expected, $hiddenFiles->values()->toArray());
    }

    /** @test */
    public function it_can_not_be_instantiated_via_the_static_make_method(): void
    {
        $this->expectException(BadMethodCallException::class);

        HiddenFiles::make([]);
    }

    public function hiddenFilesProvider(): array
    {
        return [
            'None' => [
                [], 'NOT_A_REAL_FILE', false, [],
            ],
            'Hidden files array' => [
                ['foo', 'bar', 'baz'], 'NOT_A_REAL_FILE', false, ['foo', 'bar', 'baz'],
            ],
            'Hidden files array with duplicates' => [
                ['foo', 'bar', 'foo'], 'NOT_A_REAL_FILE', false, ['foo', 'bar'],
            ],
            'Hidden files list' => [
                [], $this->filePath('.hidden'), false, ['alpha', 'bravo'],
            ],
            'App files' => [
                [], 'NOT_A_REAL_FILE', true, [
                    'app', 'index.php', '.analytics', '.env', '.env.example', '.hidden',
                ],
            ],
            'All' => [
                ['foo', 'alpha'], $this->filePath('.hidden'), true, [
                    'foo', 'alpha', 'bravo', 'app', 'index.php', '.analytics', '.env', '.env.example', '.hidden',
                ],
            ],
        ];
    }
}
