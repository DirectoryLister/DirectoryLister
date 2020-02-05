<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Breadcrumbs;
use Tests\TestCase;
use Tightenco\Collect\Support\Collection;

class BreadcrumbsTest extends TestCase
{
    public function test_it_can_parse_breadcrumbs_from_the_path(): void
    {
        $breadcrumbs = new Breadcrumbs($this->container, $this->config);

        $this->assertEquals(Collection::make([
            'foo' => '?dir=foo',
            'bar' => '?dir=foo/bar',
            'baz' => '?dir=foo/bar/baz',
        ]), $breadcrumbs('foo/bar/baz'));
    }

    public function test_it_can_parse_breadcrumbs_for_dot_path(): void
    {
        $breadcrumbs = new Breadcrumbs($this->container, $this->config);

        $this->assertEquals(new Collection, $breadcrumbs('.'));
    }

    public function test_it_can_parse_breadcrumbs_from_a_subdirectory(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/some/dir/index.php';

        $breadcrumbs = new Breadcrumbs($this->container, $this->config);

        $this->assertEquals(Collection::make([
            'foo' => '?dir=foo',
            'bar' => '?dir=foo/bar',
            'baz' => '?dir=foo/bar/baz',
        ]), $breadcrumbs('foo/bar/baz'));
    }
}
