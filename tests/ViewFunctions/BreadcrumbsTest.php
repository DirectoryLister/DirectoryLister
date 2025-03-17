<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Breadcrumbs;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tightenco\Collect\Support\Collection;

#[CoversClass(Breadcrumbs::class)]
class BreadcrumbsTest extends TestCase
{
    #[Test]
    public function it_can_parse_breadcrumbs_from_the_path(): void
    {
        $breadcrumbs = new Breadcrumbs($this->config);

        $this->assertEquals(Collection::make([
            'foo' => '?dir=foo',
            'bar' => '?dir=foo/bar',
            'baz' => '?dir=foo/bar/baz',
            'tests' => '?dir=foo/bar/baz/tests',
        ]), $breadcrumbs('foo/bar/baz/tests'));
    }

    #[Test]
    public function it_can_parse_breadcrumbs_for_dot_path(): void
    {
        $breadcrumbs = new Breadcrumbs($this->config);

        $this->assertEquals(new Collection, $breadcrumbs('.'));
    }

    #[Test]
    public function it_url_encodes_directory_names(): void
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

    #[Test]
    public function it_can_parse_breadcrumbs_from_the_path_with_back_slashes(): void
    {
        $breadcrumbs = new Breadcrumbs($this->config, '\\');

        $this->assertEquals(Collection::make([
            'foo' => '?dir=foo',
            'bar' => '?dir=foo\bar',
            'baz' => '?dir=foo\bar\baz',
        ]), $breadcrumbs('foo\bar\baz'));
    }

    #[Test]
    public function it_can_parse_breadcrumbs_from_the_path_with_zeros(): void
    {
        $breadcrumbs = new Breadcrumbs($this->config, '\\');

        $this->assertEquals(Collection::make([
            'foo' => '?dir=foo',
            '0' => '?dir=foo\0',
            'bar' => '?dir=foo\0\bar',
        ]), $breadcrumbs('foo\0\bar'));
    }
}
