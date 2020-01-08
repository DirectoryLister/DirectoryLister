<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Breadcrumbs;
use Tests\TestCase;

class BreadcrumbsTest extends TestCase
{
    public function test_it_can_parse_breadcrumbs_from_the_path(): void
    {
        $breadcrumbs = new Breadcrumbs($this->container, $this->config);

        $this->assertEquals([
            'foo' => '/foo',
            'bar' => '/foo/bar',
            'baz' => '/foo/bar/baz',
        ], $breadcrumbs('foo/bar/baz'));
    }

    public function test_it_can_parse_breadcrumbs_for_dot_path(): void
    {
        $breadcrumbs = new Breadcrumbs($this->container, $this->config);

        $this->assertEquals([], $breadcrumbs('.'));
    }
}
