<?php

declare(strict_types=1);

namespace Tests\ViewFunctions;

use App\ViewFunctions\ParentUrl;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ParentUrl::class)]
class ParentUrlTest extends TestCase
{
    #[Test]
    public function it_can_get_the_parent_directory(): void
    {
        $parentDir = new ParentUrl;

        $this->assertEquals('.', $parentDir('foo'));
        $this->assertEquals('?dir=foo', $parentDir('foo/bar'));
        $this->assertEquals('?dir=foo/bar', $parentDir('foo/bar/baz'));
        $this->assertEquals('?dir=foo/0', $parentDir('foo/0/bar'));
        $this->assertEquals('?dir=0', $parentDir('0/bar'));
    }

    #[Test]
    public function it_can_get_the_parent_directory_with_back_slashes(): void
    {
        $parentDir = new ParentUrl('\\');

        $this->assertEquals('?dir=foo', $parentDir('foo\bar'));
        $this->assertEquals('?dir=foo\bar', $parentDir('foo\bar\baz'));
        $this->assertEquals('?dir=foo\0', $parentDir('foo\0\bar'));
        $this->assertEquals('?dir=0', $parentDir('0\bar'));
    }

    public function test_parent_url_segments_are_url_encoded(): void
    {
        $parentDir = new ParentUrl;

        $this->assertEquals('?dir=foo/bar%2Bbaz', $parentDir('foo/bar+baz/qux'));
        $this->assertEquals('?dir=foo/bar%23baz', $parentDir('foo/bar#baz/qux'));
        $this->assertEquals('?dir=foo/bar%26baz', $parentDir('foo/bar&baz/qux'));
    }
}
