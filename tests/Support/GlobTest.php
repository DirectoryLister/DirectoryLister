<?php

namespace Tests\Support;

use App\Support\Glob;
use Tests\TestCase;

class GlobTest extends TestCase
{
    public function test_it_converts_glob_pattern_special_characters_to_a_regular_expression(): void
    {
        $this->assertSame('#^#', Glob::toRegex(''));
        $this->assertSame('#^\\\\#', Glob::toRegex('\\\\'));
        $this->assertSame('#^.#', Glob::toRegex('?'));
        $this->assertSame('#^[^/]*#', Glob::toRegex('*'));
        $this->assertSame('#^.*#', Glob::toRegex('**'));
        $this->assertSame('#^\##', Glob::toRegex('#'));
        $this->assertSame('#^\\?#', Glob::toRegex('\\?'));
    }

    public function test_it_converts_a_glob_pattern_to_a_regular_expression_pattern(): void
    {
        $this->assertSame('#^foo\.txt#', Glob::toRegex('foo.txt'));
        $this->assertSame('#^foo/bar\.txt#', Glob::toRegex('foo/bar.txt'));
        $this->assertSame('#^foo\?bar\.txt#', Glob::toRegex('foo\?bar.txt'));
        $this->assertSame('#^[^/]*\.txt#', Glob::toRegex('*.txt'));
        $this->assertSame('#^.*/[^/]*\.txt#', Glob::toRegex('**/*.txt'));
        $this->assertSame('#^([^/]*|.*/[^/]*)\.txt#', Glob::toRegex('{*,**/*}.txt'));
        $this->assertSame('#^file\.(yml|yaml)#', Glob::toRegex('file.{yml,yaml}'));
        $this->assertSame('#^[fbw]oo\.txt#', Glob::toRegex('[fbw]oo.txt'));
        $this->assertSame('#^[^fbw]oo\.txt#', Glob::toRegex('[^fbw]oo.txt'));
        $this->assertSame('#^foo}bar\.txt#', Glob::toRegex('foo}bar.txt'));
        $this->assertSame('#^foo\^bar\.txt#', Glob::toRegex('foo^bar.txt'));
        $this->assertSame('#^foo,bar\.txt#', Glob::toRegex('foo,bar.txt'));
        $this->assertSame('#^foo/.*/[^/]*\.txt#', Glob::toRegex('foo/**/*.txt'));
    }
}
