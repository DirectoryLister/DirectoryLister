<?php

declare(strict_types=1);

namespace Tests\ViewFunctions;

use App\ViewFunctions\FileUrl;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(FileUrl::class)]
class FileUrlTest extends TestCase
{
    #[Test]
    public function it_can_return_a_url(): void
    {
        $this->container->set('direct_links', '**/index.{htm,html},**/*.php');

        $url = $this->container->get(FileUrl::class);

        // Root
        $this->assertEquals('', $url('/'));
        $this->assertEquals('', $url('./'));

        // Subdirectories
        $this->assertEquals('?dir=some/path', $url('some/path'));
        $this->assertEquals('?dir=some/path', $url('./some/path'));
        $this->assertEquals('?dir=some/path', $url('./some/path'));
        $this->assertEquals('?dir=some/file.test', $url('some/file.test'));
        $this->assertEquals('?dir=some/file.test', $url('./some/file.test'));
        $this->assertEquals('?dir=0/path', $url('0/path'));
        $this->assertEquals('?dir=1/path', $url('1/path'));
        $this->assertEquals('?dir=0', $url('0'));

        // Files
        $this->assertEquals('?file=subdir/alpha.scss', $url('subdir/alpha.scss'));
        $this->assertEquals('?file=subdir/bravo.js', $url('subdir/bravo.js'));
        $this->assertEquals('?file=subdir/charlie.bash', $url('subdir/charlie.bash'));

        // Direct Links
        $this->assertEquals('direct_links/index.htm', $url('direct_links/index.htm'));
        $this->assertEquals('direct_links/index.html', $url('direct_links/index.html'));
        $this->assertEquals('direct_links/test.php', $url('direct_links/test.php'));
    }

    #[Test]
    public function it_can_return_a_url_with_back_slashes(): void
    {
        $url = $this->container->make(FileUrl::class, ['directorySeparator' => '\\']);

        $this->assertEquals('', $url('\\'));
        $this->assertEquals('', $url('.\\'));
        $this->assertEquals('?dir=some\path', $url('some\path'));
        $this->assertEquals('?dir=some\path', $url('.\some\path'));
        $this->assertEquals('?dir=some\file.test', $url('some\file.test'));
        $this->assertEquals('?dir=some\file.test', $url('.\some\file.test'));
        $this->assertEquals('?dir=0\path', $url('0\path'));
        $this->assertEquals('?dir=1\path', $url('1\path'));
    }

    #[Test]
    public function url_segments_are_url_encoded(): void
    {
        $url = $this->container->get(FileUrl::class);

        $this->assertEquals('?dir=foo/bar%2Bbaz', $url('foo/bar+baz'));
        $this->assertEquals('?dir=foo/bar%23baz', $url('foo/bar#baz'));
        $this->assertEquals('?dir=foo/bar%26baz', $url('foo/bar&baz'));
    }
}
