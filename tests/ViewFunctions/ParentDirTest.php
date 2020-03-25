<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\ParentDir;
use Tests\TestCase;

class ParentDirTest extends TestCase
{
    public function test_it_can_get_the_parent_directory_when_one_level_deep(): void
    {
        $parentDir = new ParentDir;

        $this->assertEquals('.', $parentDir('foo'));
    }

    public function test_it_can_get_the_parent_directory_when_two_levels_deep(): void
    {
        $parentDir = new ParentDir;

        $this->assertEquals('?dir=foo', $parentDir('foo/bar'));
    }

    public function test_it_can_get_the_parent_directory_when_three_levels_deep(): void
    {
        $parentDir = new ParentDir;

        $this->assertEquals('?dir=foo/bar', $parentDir('foo/bar/baz'));
    }

    public function test_it_can_get_the_parent_directory_from_a_path_in_a_subdirectory(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/some/dir/index.php';

        $parentDir = new ParentDir;

        $this->assertEquals('?dir=foo/bar', $parentDir('foo/bar/baz'));
    }
}
