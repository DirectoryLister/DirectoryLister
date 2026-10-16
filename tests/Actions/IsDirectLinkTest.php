<?php

declare(strict_types=1);

namespace Tests\Actions;

use App\Actions\IsDirectLink;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(IsDirectLink::class)]
class IsDirectLinkTest extends TestCase
{
    #[Test]
    public function it_matches_direct_link_paths(): void
    {
        $this->container->set('direct_links', 'direct_links/**');

        $isDirectLink = $this->container->get(IsDirectLink::class);

        $this->assertTrue($isDirectLink($this->filePath('direct_links')));
        $this->assertTrue($isDirectLink($this->filePath('direct_links/index.htm')));
        $this->assertFalse($isDirectLink($this->filePath('subdir')));
        $this->assertFalse($isDirectLink($this->filePath('golf.txt')));
    }

    #[Test]
    public function it_does_not_match_when_direct_links_are_disabled(): void
    {
        $this->container->set('direct_links', null);

        $isDirectLink = $this->container->get(IsDirectLink::class);

        $this->assertFalse($isDirectLink($this->filePath('direct_links')));
    }

    #[Test]
    public function it_does_not_match_when_base_path_differs_from_files_path(): void
    {
        $this->container->set('direct_links', 'direct_links/**');
        $this->container->set('base_path', '/some/other/path');

        $isDirectLink = $this->container->get(IsDirectLink::class);

        $this->assertFalse($isDirectLink($this->filePath('direct_links')));
    }
}
