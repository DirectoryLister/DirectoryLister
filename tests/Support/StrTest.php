<?php

namespace Tests\Support;

use App\Support\Str;
use Tests\TestCase;
use Tightenco\Collect\Support\Collection;

/** @covers \App\Support\Str */
class StrTest extends TestCase
{
    public function test_it_can_create_a_collection_from_a_string(): void
    {
        $this->assertEquals(
            Collection::make(['foo', 'bar', 'baz']),
            Str::explode('foo bar baz', ' ')
        );

        $this->assertEquals(
            Collection::make(['foo', 'bar', 'baz']),
            Str::explode('foo/bar/baz', '/')
        );

        $this->assertEquals(
            Collection::make(['foo bar baz']),
            Str::explode('foo bar baz', '/')
        );
    }
}
