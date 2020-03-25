<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Markdown;
use Tests\TestCase;

class MarkdownTest extends TestCase
{
    public function test_it_can_parse_markdown_into_html(): void
    {
        $this->assertEquals(
            '<p><strong>Test</strong> <code>markdown</code>, <del>please</del> <em>ignore</em></p>',
            (new Markdown)('**Test** `markdown`, ~~please~~ _ignore_')
        );
    }
}
