<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Exceptions\InvalidConfiguration;
use App\Factories\FinderFactory;
use App\HiddenFiles;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Tests\TestCase;

#[CoversClass(FinderFactory::class)]
class FinderFactoryTest extends TestCase
{
    #[Test]
    public function it_can_compose_the_finder_component(): void
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

    #[Test]
    public function it_can_sort_by_a_user_provided_closure(): void
    {
        $this->container->set('sort_order', \DI\value(
            static fn (SplFileInfo $file1, SplFileInfo $file2) => $file1->getSize() <=> $file2->getSize()
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

    #[Test]
    public function it_can_reverse_the_sort_order(): void
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

    #[Test]
    public function it_does_not_return_hidden_files(): void
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

    public function test_dot_files_are_returned(): void
    {
        $this->container->set('hidden_files', []);
        $this->container->set('hide_dot_files', false);

        $finder = (new FinderFactory(
            $this->container,
            $this->config,
            HiddenFiles::fromConfig($this->config)
        ))();
        $finder->in($this->filePath('subdir'))->depth(0);

        $this->assertInstanceOf(Finder::class, $finder);
        $this->assertEquals([
            '.dot_dir',
            'alpha.scss',
            'bravo.js',
            'charlie.bash',
            'delta.html',
            'echo.yaml',
        ], $this->getFilesArray($finder));
    }

    public function test_dot_directory_contents_are_returned(): void
    {
        $this->container->set('hidden_files', []);
        $this->container->set('hide_dot_files', false);

        $finder = (new FinderFactory(
            $this->container,
            $this->config,
            HiddenFiles::fromConfig($this->config)
        ))();
        $finder->in($this->filePath('subdir/.dot_dir'))->depth(0);

        $this->assertInstanceOf(Finder::class, $finder);
        $this->assertEquals(['.dot_file'], $this->getFilesArray($finder));
    }

    #[Test]
    public function it_throws_a_runtime_exception_with_an_invalid_sort_order(): void
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
        $files = array_map(
            static fn (SplFileInfo $file): string => $file->getFilename(),
            iterator_to_array($finder)
        );

        return array_values($files);
    }
}
