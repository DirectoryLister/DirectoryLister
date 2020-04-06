<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Url;
use Tests\TestCase;

class UrlTest extends TestCase
{
    public function test_it_can_return_a_url_for_a_directory(): void
    {
        $url = new Url;

        $this->assertEquals('', $url('/'));
        $this->assertEquals('', $url('./'));
        $this->assertEquals('?dir=some/path', $url('some/path'));
        $this->assertEquals('?dir=some/path', $url('./some/path'));
        $this->assertEquals('?dir=some/path', $url('./some/path'));
        $this->assertEquals('?dir=some/file.test', $url('some/file.test'));
        $this->assertEquals('?dir=some/file.test', $url('./some/file.test'));
    }

    public function test_it_can_return_a_url_for_a_directory_using_back_slashes(): void
    {
        $url = new Url('\\');

        $this->assertEquals('', $url('\\'));
        $this->assertEquals('', $url('.\\'));
        $this->assertEquals('?dir=some\path', $url('some\path'));
        $this->assertEquals('?dir=some\path', $url('.\some\path'));
        $this->assertEquals('?dir=some\file.test', $url('some\file.test'));
        $this->assertEquals('?dir=some\file.test', $url('.\some\file.test'));
    }

    public function test_it_can_return_a_url_for_a_file(): void
    {
        chdir($this->filePath('.'));

        $url = new Url;

        $this->assertEquals('README.md', $url('README.md'));
        $this->assertEquals('README.md', $url('./README.md'));
        $this->assertEquals('subdir/alpha.scss', $url('subdir/alpha.scss'));
        $this->assertEquals('subdir/alpha.scss', $url('./subdir/alpha.scss'));
    }

    public function test_it_url_encodes_directory_names(): void
    {
        $url = new Url;

        $this->assertEquals('?dir=foo/bar%2Bbaz', $url('foo/bar+baz'));
        $this->assertEquals('?dir=foo/bar%23baz', $url('foo/bar#baz'));
        $this->assertEquals('?dir=foo/bar%26baz', $url('foo/bar&baz'));
    }
}
