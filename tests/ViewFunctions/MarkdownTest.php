<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Markdown;
use Tests\TestCase;

class MarkdownTest extends TestCase
{
    public function test_it_can_parse_markdown_into_html(): void
    {
        $markdown = new Markdown($this->container, $this->config);

        $this->assertEquals(
            '<p><strong>Test</strong> <code>markdown</code>, <del>please</del> <em>ignore</em></p>',
            $markdown('**Test** `markdown`, ~~please~~ _ignore_')
        );
    }
}
