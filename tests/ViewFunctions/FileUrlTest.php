<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\FileUrl;
use Tests\TestCase;

/** @covers \App\ViewFunctions\FileUrl */
class FileUrlTest extends TestCase
{
    public function test_it_can_return_a_url(): void
    {
        $url = new FileUrl;

        $this->assertEquals('', $url('/'));
        $this->assertEquals('', $url('./'));
        $this->assertEquals('?dir=some/path', $url('some/path'));
        $this->assertEquals('?dir=some/path', $url('./some/path'));
        $this->assertEquals('?dir=some/path', $url('./some/path'));
        $this->assertEquals('?dir=some/file.test', $url('some/file.test'));
        $this->assertEquals('?dir=some/file.test', $url('./some/file.test'));
        $this->assertEquals('?dir=0/path', $url('0/path'));
        $this->assertEquals('?dir=1/path', $url('1/path'));
        $this->assertEquals('?dir=0', $url('0'));
    }

    public function test_it_can_return_a_url_with_back_slashes(): void
    {
        $url = new FileUrl('\\');

        $this->assertEquals('', $url('\\'));
        $this->assertEquals('', $url('.\\'));
        $this->assertEquals('?dir=some\path', $url('some\path'));
        $this->assertEquals('?dir=some\path', $url('.\some\path'));
        $this->assertEquals('?dir=some\file.test', $url('some\file.test'));
        $this->assertEquals('?dir=some\file.test', $url('.\some\file.test'));
        $this->assertEquals('?dir=0\path', $url('0\path'));
        $this->assertEquals('?dir=1\path', $url('1\path'));
    }

    public function test_url_segments_are_url_encoded(): void
    {
        $url = new FileUrl;

        $this->assertEquals('?dir=foo/bar%2Bbaz', $url('foo/bar+baz'));
        $this->assertEquals('?dir=foo/bar%23baz', $url('foo/bar#baz'));
        $this->assertEquals('?dir=foo/bar%26baz', $url('foo/bar&baz'));
    }
}
