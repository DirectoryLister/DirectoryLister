<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Url;
use RuntimeException;
use Tests\TestCase;

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
    }

    public function test_it_can_return_a_directory_url(): void
    {
        $url = new Url;

        $this->assertEquals('', $url('/', 'dir'));
        $this->assertEquals('', $url('./', 'dir'));
        $this->assertEquals('?dir=some/path', $url('some/path', 'dir'));
        $this->assertEquals('?dir=some/path', $url('./some/path', 'dir'));
        $this->assertEquals('?dir=some/path', $url('./some/path', 'dir'));
        $this->assertEquals('?dir=some/file.test', $url('some/file.test', 'dir'));
        $this->assertEquals('?dir=some/file.test', $url('./some/file.test', 'dir'));
    }

    public function test_it_can_return_a_file_info_url(): void
    {
        $url = new Url;

        $this->assertEquals('?info=.', $url('/', 'info'));
        $this->assertEquals('?info=.', $url('./', 'info'));
        $this->assertEquals('?info=some/path', $url('some/path', 'info'));
        $this->assertEquals('?info=some/path', $url('./some/path', 'info'));
        $this->assertEquals('?info=some/path', $url('./some/path', 'info'));
        $this->assertEquals('?info=some/file.test', $url('some/file.test', 'info'));
        $this->assertEquals('?info=some/file.test', $url('./some/file.test', 'info'));
    }

    public function test_it_can_return_a_zip_url(): void
    {
        $url = new Url;

        $this->assertEquals('?zip=.', $url('/', 'zip'));
        $this->assertEquals('?zip=.', $url('./', 'zip'));
        $this->assertEquals('?zip=some/path', $url('some/path', 'zip'));
        $this->assertEquals('?zip=some/path', $url('./some/path', 'zip'));
        $this->assertEquals('?zip=some/path', $url('./some/path', 'zip'));
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
        $this->assertEquals('?dir=some\path', $url('some\path', 'dir'));
        $this->assertEquals('?info=some\path', $url('some\path', 'info'));
        $this->assertEquals('?zip=some\path', $url('some\path', 'zip'));
    }

    public function test_it_throws_an_exception_for_an_invalid_action(): void
    {
        $url = new Url;

        $this->expectException(RuntimeException::class);

        $url('some/path', 'INVALID_ACTION');
    }

    public function test_segments_are_url_encoded(): void
    {
        $url = new Url;

        $this->assertEquals('foo/bar%2Bbaz', $url('foo/bar+baz'));
        $this->assertEquals('foo/bar%23baz', $url('foo/bar#baz'));
        $this->assertEquals('foo/bar%26baz', $url('foo/bar&baz'));
    }
}
