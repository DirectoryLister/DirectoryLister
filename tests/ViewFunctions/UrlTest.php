<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Url;
use Tests\TestCase;

class UrlTest extends TestCase
{
    public function test_it_can_return_a_url_for_a_directory(): void
    {
        $url = new Url;

        // Forward slashes
        $this->assertEquals('', $url('/'));
        $this->assertEquals('', $url('./'));
        $this->assertEquals('?dir=some/path', $url('some/path'));
        $this->assertEquals('?dir=some/path', $url('./some/path'));
        $this->assertEquals('?dir=some/path', $url('./some/path'));
        $this->assertEquals('?dir=some/file.test', $url('some/file.test'));
        $this->assertEquals('?dir=some/file.test', $url('./some/file.test'));

        // Back slashes
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
}
