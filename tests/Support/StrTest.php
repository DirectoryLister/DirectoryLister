<?php

namespace Tests\Support;

use App\Support\Str;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Support\Collection;

#[CoversClass(Str::class)]
class StrTest extends TestCase
{
    #[Test]
    public function it_can_create_a_collection_from_a_string(): void
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
