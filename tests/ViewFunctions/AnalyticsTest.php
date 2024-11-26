<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Analytics;
use Tests\TestCase;

/** @covers \App\ViewFunctions\Analytics */
class AnalyticsTest extends TestCase
{
    public function test_it_can_return_the_analytics_file_contents(): void
    {
        $analytics = new Analytics($this->config);

        $this->assertEquals('<!-- Test file; please ignore -->', $analytics());
    }

    public function test_it_does_not_return_anything_when_the_analytics_file_does_not_exist(): void
    {
        $this->container->set('analytics_file', 'NONEXISTENT_FILE');
        $analytics = new Analytics($this->config);

        $this->assertEquals('', $analytics());
    }
}
