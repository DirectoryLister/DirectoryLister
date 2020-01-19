<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\ParentDir;
use Tests\TestCase;

class ParentDirTest extends TestCase
{
    public function test_it_can_get_the_parent_directory_from_a_path(): void
    {
        $parentDir = new ParentDir($this->container, $this->config);

        $this->assertEquals('foo/bar', $parentDir('foo/bar/baz'));
    }
}
