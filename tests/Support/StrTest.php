<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Support\Str;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

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
