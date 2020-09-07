<?php

namespace Tests\Factories;

use App\Exceptions\InvalidConfiguration;
use App\Factories\FinderFactory;
use App\HiddenFiles;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Tests\TestCase;

/** @covers \App\Factories\FinderFactory */
class FinderFactoryTest extends TestCase
{
    public function test_it_can_compose_the_finder_component(): void
    {
        $finder = (new FinderFactory(
            $this->container,
            $this->config,
            HiddenFiles::fromConfig($this->config)
        ))();

        $this->assertInstanceOf(Finder::class, $finder);

        $finder->in($this->filePath('subdir'))->depth(0);

        $this->assertEquals([
            'alpha.scss',
            'bravo.js',
            'charlie.bash',
            'delta.html',
            'echo.yaml',
        ], $this->getFilesArray($finder));
    }

    public function test_it_can_sort_by_a_user_provided_closure(): void
    {
        $this->container->set('sort_order', \DI\value(
            static function (SplFileInfo $file1, SplFileInfo $file2) {
                return $file1->getSize() <=> $file2->getSize();
            }
        ));

        $finder = (new FinderFactory(
            $this->container,
            $this->config,
            HiddenFiles::fromConfig($this->config)
        ))();
        $finder->in($this->filePath('subdir'))->depth(0);

        $this->assertEquals([
            'alpha.scss',
            'bravo.js',
            'echo.yaml',
            'charlie.bash',
            'delta.html',
        ], $this->getFilesArray($finder));
    }

    public function test_it_can_reverse_the_sort_order(): void
    {
        $this->container->set('reverse_sort', true);

        $finder = (new FinderFactory(
            $this->container,
            $this->config,
            HiddenFiles::fromConfig($this->config)
        ))();
        $finder->in($this->filePath('subdir'))->depth(0);

        $this->assertEquals([
            'echo.yaml',
            'delta.html',
            'charlie.bash',
            'bravo.js',
            'alpha.scss',
        ], $this->getFilesArray($finder));
    }

    public function test_it_does_not_return_hidden_files(): void
    {
        $this->container->set('hidden_files', [
            'subdir/alpha.scss', 'subdir/charlie.bash', '**/*.yaml',
        ]);

        $finder = (new FinderFactory(
            $this->container,
            $this->config,
            HiddenFiles::fromConfig($this->config)
        ))();
        $finder->in($this->filePath('subdir'))->depth(0);

        $this->assertInstanceOf(Finder::class, $finder);
        $this->assertEquals([
            'bravo.js',
            'delta.html',
        ], $this->getFilesArray($finder));
    }

    public function test_it_throws_a_runtime_exception_with_an_invalid_sort_order(): void
    {
        $this->container->set('sort_order', 'invalid');

        $this->expectException(InvalidConfiguration::class);

        (new FinderFactory(
            $this->container,
            $this->config,
            HiddenFiles::fromConfig($this->config)
        ))();
    }

    protected function getFilesArray(Finder $finder): array
    {
        $files = array_map(static function (SplFileInfo $file) {
            return $file->getFilename();
        }, iterator_to_array($finder));

        return array_values($files);
    }
}
