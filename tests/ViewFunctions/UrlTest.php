<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Url;
use Tests\TestCase;

/** @covers \App\ViewFunctions\Url */
class UrlTest extends TestCase
{
    public function test_it_can_return_a_url(): void
    {
        $url = new Url;

        $this->assertEquals('', $url('/'));
        $this->assertEquals('', $url('./'));
        $this->assertEquals('some/path', $url('some/path'));
        $this->assertEquals('some/path', $url('./some/path'));
        $this->assertEquals('some/path', $url('./some/path'));
        $this->assertEquals('some/file.test', $url('some/file.test'));
        $this->assertEquals('some/file.test', $url('./some/file.test'));
        $this->assertEquals('0/path', $url('0/path'));
        $this->assertEquals('1/path', $url('1/path'));
        $this->assertEquals('0', $url('0'));
    }

    public function test_it_can_return_a_url_with_back_slashes(): void
    {
        $url = new Url('\\');

        $this->assertEquals('', $url('\\'));
        $this->assertEquals('', $url('.\\'));
        $this->assertEquals('some\path', $url('some\path'));
        $this->assertEquals('some\path', $url('.\some\path'));
        $this->assertEquals('some\file.test', $url('some\file.test'));
        $this->assertEquals('some\file.test', $url('.\some\file.test'));
        $this->assertEquals('0\path', $url('0\path'));
        $this->assertEquals('1\path', $url('1\path'));
    }

    public function test_url_segments_are_url_encoded(): void
    {
        $url = new Url;

        $this->assertEquals('foo/bar%2Bbaz', $url('foo/bar+baz'));
        $this->assertEquals('foo/bar%23baz', $url('foo/bar#baz'));
        $this->assertEquals('foo/bar%26baz', $url('foo/bar&baz'));
    }
}
