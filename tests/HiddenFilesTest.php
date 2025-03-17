<?php

declare(strict_types=1);

namespace Tests;

use App\HiddenFiles;
use BadMethodCallException;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(HiddenFiles::class)]
class HiddenFilesTest extends TestCase
{
    #[DataProvider('hiddenFilesProvider')]
    public function it_creates_a_collection_of_hidden_files(
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

    #[Test]
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
