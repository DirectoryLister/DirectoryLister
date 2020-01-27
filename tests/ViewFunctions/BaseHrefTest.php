<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\BaseHref;
use Tests\TestCase;

class BaseHrefTest extends TestCase
{
    public function teardown(): void
    {
        unset($_SERVER['HTTPS'], $_SERVER['SCRIPT_NAME']);

        parent::tearDown();
    }

    public function test_it_can_return_the_base_href(): void
    {
        $baseHref = new BaseHref($this->container, $this->config);

        $this->assertEquals('http://localhost/', $baseHref());
    }

    public function test_it_return_the_base_href_when_using_https(): void
    {
        $_SERVER['HTTPS'] = 'on';

        $baseHref = new BaseHref($this->container, $this->config);

        $this->assertEquals('https://localhost/', $baseHref());
    }

    public function test_it_can_return_the_base_href_when_in_a_subdirectory(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/some/dir/index.php';

        $baseHref = new BaseHref($this->container, $this->config);

        $this->assertEquals('http://localhost/some/dir/', $baseHref());
    }
}
