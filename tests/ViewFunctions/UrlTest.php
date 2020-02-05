<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Url;
use Tests\TestCase;

class UrlTest extends TestCase
{
    public function test_it_can_return_a_url(): void
    {
        $url = new Url($this->container, $this->config);

        $this->assertEquals('', $url('/'));
        $this->assertEquals('?dir=some/path', $url('some/path'));
        $this->assertEquals('?dir=some/file.test', $url('some/file.test'));
    }

    public function test_it_can_return_a_url_in_a_subdirectory(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/some/dir/index.php';

        $url = new Url($this->container, $this->config);

        $this->assertEquals('', $url('/'));
        $this->assertEquals('?dir=some/path', $url('some/path'));
        $this->assertEquals('?dir=some/file.test', $url('some/file.test'));
    }
}
