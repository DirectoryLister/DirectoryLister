<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\ZipUrl;
use Tests\TestCase;

/** @covers \App\ViewFunctions\ZipUrl */
class ZipUrlTest extends TestCase
{
    public function test_it_can_return_a_url(): void
    {
        $url = new ZipUrl;

        $this->assertEquals('?zip=.', $url('/'));
        $this->assertEquals('?zip=.', $url('./'));
        $this->assertEquals('?zip=some/path', $url('some/path'));
        $this->assertEquals('?zip=some/path', $url('./some/path'));
        $this->assertEquals('?zip=some/path', $url('./some/path'));
        $this->assertEquals('?zip=some/file.test', $url('some/file.test'));
        $this->assertEquals('?zip=some/file.test', $url('./some/file.test'));
        $this->assertEquals('?zip=0/path', $url('0/path'));
        $this->assertEquals('?zip=1/path', $url('1/path'));
        $this->assertEquals('?zip=0', $url('0'));
    }

    public function test_it_can_return_a_url_with_back_slashes(): void
    {
        $url = new ZipUrl('\\');

        $this->assertEquals('?zip=.', $url('\\'));
        $this->assertEquals('?zip=.', $url('.\\'));
        $this->assertEquals('?zip=some\path', $url('some\path'));
        $this->assertEquals('?zip=some\path', $url('.\some\path'));
        $this->assertEquals('?zip=some\file.test', $url('some\file.test'));
        $this->assertEquals('?zip=some\file.test', $url('.\some\file.test'));
    }

    public function test_url_segments_are_url_encoded(): void
    {
        $url = new ZipUrl;

        $this->assertEquals('?zip=foo/bar%2Bbaz', $url('foo/bar+baz'));
        $this->assertEquals('?zip=foo/bar%23baz', $url('foo/bar#baz'));
        $this->assertEquals('?zip=foo/bar%26baz', $url('foo/bar&baz'));
    }
}
