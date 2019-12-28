<?php

namespace Tests\Unit\Support;

use App\Support\Helpers;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function test_it_can_get_an_environment_variable()
    {
        putenv('TEST_STRING=Test string; please ignore');

        $env = Helpers::env('TEST_STRING');

        $this->assertEquals('Test string; please ignore', $env);
    }

    public function test_it_can_return_a_default_value()
    {
        $env = Helpers::env('DEFAULT_TEST', 'Test default; please ignore');

        $this->assertEquals('Test default; please ignore', $env);
    }

    public function test_it_can_a_retrieve_boolean_value()
    {
        putenv('TRUE_TEST=true');
        putenv('FALSE_TEST=false');

        $this->assertTrue(Helpers::env('TRUE_TEST'));
        $this->assertFalse(Helpers::env('FALSE_TEST'));
    }

    public function test_it_can_retrieve_a_null_value()
    {
        putenv('NULL_TEST=null');

        $this->assertNull(Helpers::env('NULL_TEST'));
    }

    public function test_it_can_be_surrounded_bys_quotation_marks()
    {
        putenv('QUOTES_TEST="Test charlie; please ignore"');

        $env = Helpers::env('QUOTES_TEST');

        $this->assertEquals('Test charlie; please ignore', $env);
    }
}
