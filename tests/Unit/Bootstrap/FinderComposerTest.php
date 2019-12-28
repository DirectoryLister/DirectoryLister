<?php

namespace Tests\Unit\Bootstrap;

use App\Bootstrap\FinderComposer;
use RuntimeException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Tests\TestCase;

class FinderComposerTest extends TestCase
{
    public function test_it_can_compose_the_finder_component(): void
    {
        (new FinderComposer($this->container, $this->config))();

        $finder = $this->container->get(Finder::class);
        $finder->in($this->container->get('app.root'));

        $this->assertInstanceOf(Finder::class, $finder);
        foreach ($finder as $file) {
            $this->assertInstanceOf(SplFileInfo::class, $file);
        }
    }

    public function test_it_can_sort_by_a_user_provided_closure(): void
    {
        $this->config->set('app.sort_order', function (SplFileInfo $file1, SplFileInfo $file2) {
            return $file1->getSize() <=> $file2->getSize();
        });

        (new FinderComposer($this->container, $this->config))();

        $finder = $this->container->get(Finder::class);
        $finder->in($this->container->get('app.root'));

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
        $this->config->set('app.reverse_sort', true);

        (new FinderComposer($this->container, $this->config))();

        $finder = $this->container->get(Finder::class);
        $finder->in($this->container->get('app.root'));

        $this->assertEquals([
            'echo.yaml',
            'delta.html',
            'charlie.bash',
            'bravo.js',
            'alpha.scss',
        ], $this->getFilesArray($finder));
    }

    public function test_it_throws_a_runtime_exception_with_an_invalid_sort_order(): void
    {
        $this->config->set('app.sort_order', 'invalid');

        $this->expectException(RuntimeException::class);

        (new FinderComposer($this->container, $this->config))();
    }

    protected function getFilesArray(Finder $finder): array
    {
        $files = array_map(function (SplFileInfo $file) {
            return $file->getFilename();
        }, iterator_to_array($finder));

        return array_values($files);
    }
}
