<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\ParentUrl;
use Tests\TestCase;

class ParentUrlTest extends TestCase
{
    public function test_it_can_get_the_parent_directory_when_one_level_deep(): void
    {
        $parentDir = new ParentUrl;

        $this->assertEquals('.', $parentDir('foo'));
    }

    public function test_it_can_get_the_parent_directory_when_two_levels_deep(): void
    {
        $parentDir = new ParentUrl;

        $this->assertEquals('?dir=foo', $parentDir('foo/bar'));
    }

    public function test_it_can_get_the_parent_directory_when_three_levels_deep(): void
    {
        $parentDir = new ParentUrl;

        $this->assertEquals('?dir=foo/bar', $parentDir('foo/bar/baz'));
    }

    public function test_it_can_get_the_parent_directory_from_a_path_in_a_subdirectory(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/some/dir/index.php';

        $parentDir = new ParentUrl;

        $this->assertEquals('?dir=foo/bar', $parentDir('foo/bar/baz'));
    }

    public function test_it_can_get_the_parent_directory_with_back_slashes(): void
    {
        $parentDir = new ParentUrl('\\');

        $this->assertEquals('?dir=foo\bar', $parentDir('foo\bar\baz'));
    }

    public function test_parent_url_segments_are_url_encoded(): void
    {
        $parentDir = new ParentUrl;

        $this->assertEquals('?dir=foo/bar%2Bbaz', $parentDir('foo/bar+baz/qux'));
        $this->assertEquals('?dir=foo/bar%23baz', $parentDir('foo/bar#baz/qux'));
        $this->assertEquals('?dir=foo/bar%26baz', $parentDir('foo/bar&baz/qux'));
    }
}
