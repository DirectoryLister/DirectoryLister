<?php

namespace Tests\Providers;

use App\Providers\FinderProvider;
use RuntimeException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Tests\TestCase;

class FinderProviderTest extends TestCase
{
    public function test_it_can_compose_the_finder_component(): void
    {
        (new FinderProvider($this->container, $this->config))();

        $finder = $this->container->get(Finder::class);
        $finder->in($this->container->get('base_path'))->depth(0);

        $this->assertInstanceOf(Finder::class, $finder);
        $this->assertEquals([
            'subdir',
            'alpha.scss',
            'bravo.js',
            'charlie.bash',
            'delta.html',
            'echo.yaml',
        ], $this->getFilesArray($finder));
    }

    public function test_it_can_sort_by_a_user_provided_closure(): void
    {
        $this->config->set('app.sort_order', function (SplFileInfo $file1, SplFileInfo $file2) {
            return $file1->getSize() <=> $file2->getSize();
        });

        (new FinderProvider($this->container, $this->config))();

        $finder = $this->container->get(Finder::class);
        $finder->in($this->container->get('base_path'))->depth(0);

        $this->assertEquals([
            'alpha.scss',
            'bravo.js',
            'echo.yaml',
            'charlie.bash',
            'delta.html',
            'subdir',
        ], $this->getFilesArray($finder));
    }

    public function test_it_can_reverse_the_sort_order(): void
    {
        $this->config->set('app.reverse_sort', true);

        (new FinderProvider($this->container, $this->config))();

        $finder = $this->container->get(Finder::class);
        $finder->in($this->container->get('base_path'))->depth(0);

        $this->assertEquals([
            'echo.yaml',
            'delta.html',
            'charlie.bash',
            'bravo.js',
            'alpha.scss',
            'subdir',
        ], $this->getFilesArray($finder));
    }

    public function test_it_throws_a_runtime_exception_with_an_invalid_sort_order(): void
    {
        $this->config->set('app.sort_order', 'invalid');

        $this->expectException(RuntimeException::class);

        (new FinderProvider($this->container, $this->config))();
    }

    protected function getFilesArray(Finder $finder): array
    {
        $files = array_map(function (SplFileInfo $file) {
            return $file->getFilename();
        }, iterator_to_array($finder));

        return array_values($files);
    }
}
