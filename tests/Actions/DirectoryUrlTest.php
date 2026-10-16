<?php

declare(strict_types=1);

namespace Tests\Actions;

use App\Actions\DirectoryUrl;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(DirectoryUrl::class)]
class DirectoryUrlTest extends TestCase
{
    #[Test]
    public function it_returns_a_query_string_url_by_default(): void
    {
        $directoryUrl = $this->container->get(DirectoryUrl::class);

        $this->assertEquals('?dir=foo', $directoryUrl('foo'));
        $this->assertEquals('?dir=foo/bar%2Bbaz', $directoryUrl('foo/bar+baz'));
    }

    #[Test]
    public function it_returns_a_path_url_for_a_direct_linked_directory(): void
    {
        $this->container->set('direct_links', 'direct_links/**');

        $directoryUrl = $this->container->get(DirectoryUrl::class);

        $this->assertEquals('direct_links', $directoryUrl('direct_links'));
        $this->assertEquals('direct_links/subdir', $directoryUrl('direct_links/subdir'));
        $this->assertEquals('?dir=subdir', $directoryUrl('subdir'));
    }

    #[Test]
    public function it_returns_a_query_string_url_when_direct_links_are_disabled(): void
    {
        $this->container->set('direct_links', null);

        $directoryUrl = $this->container->get(DirectoryUrl::class);

        $this->assertEquals('?dir=direct_links/subdir', $directoryUrl('direct_links/subdir'));
    }
}
