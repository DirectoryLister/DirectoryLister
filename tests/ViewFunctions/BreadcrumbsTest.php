<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Breadcrumbs;
use Tests\TestCase;
use Tightenco\Collect\Support\Collection;

/** @covers \App\ViewFunctions\Breadcrumbs */
class BreadcrumbsTest extends TestCase
{
    public function test_it_can_parse_breadcrumbs_from_the_path(): void
    {
        $breadcrumbs = new Breadcrumbs($this->config);

        $this->assertEquals(Collection::make([
            'foo' => '?dir=foo',
            'bar' => '?dir=foo/bar',
            'baz' => '?dir=foo/bar/baz',
            'tests' => '?dir=foo/bar/baz/tests',
        ]), $breadcrumbs('foo/bar/baz/tests'));
    }

    public function test_it_can_parse_breadcrumbs_for_dot_path(): void
    {
        $breadcrumbs = new Breadcrumbs($this->config);

        $this->assertEquals(new Collection, $breadcrumbs('.'));
    }

    public function test_it_url_encodes_directory_names(): void
    {
        $breadcrumbs = new Breadcrumbs($this->config);

        $this->assertEquals(Collection::make([
            'foo' => '?dir=foo',
            'bar+baz' => '?dir=foo/bar%2Bbaz',
        ]), $breadcrumbs('foo/bar+baz'));

        $this->assertEquals(Collection::make([
            'foo' => '?dir=foo',
            'bar#baz' => '?dir=foo/bar%23baz',
        ]), $breadcrumbs('foo/bar#baz'));

        $this->assertEquals(Collection::make([
            'foo' => '?dir=foo',
            'bar&baz' => '?dir=foo/bar%26baz',
        ]), $breadcrumbs('foo/bar&baz'));
    }

    public function test_it_can_parse_breadcrumbs_from_the_path_with_back_slashes(): void
    {
        $breadcrumbs = new Breadcrumbs($this->config, '\\');

        $this->assertEquals(Collection::make([
            'foo' => '?dir=foo',
            'bar' => '?dir=foo\bar',
            'baz' => '?dir=foo\bar\baz',
        ]), $breadcrumbs('foo\bar\baz'));
    }

    public function test_it_can_parse_breadcrumbs_from_the_path_with_zeros(): void
    {
        $breadcrumbs = new Breadcrumbs($this->config, '\\');

        $this->assertEquals(Collection::make([
            'foo' => '?dir=foo',
            '0' => '?dir=foo\0',
            'bar' => '?dir=foo\0\bar',
        ]), $breadcrumbs('foo\0\bar'));
    }
}
