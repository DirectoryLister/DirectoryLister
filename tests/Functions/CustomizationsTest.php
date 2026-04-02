<?php

declare(strict_types=1);

namespace Tests\Functions;

use App\Functions\Customizations;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Customizations::class)]
class CustomizationsTest extends TestCase
{
    #[Test]
    public function it_can_return_the_customizations_file_contents(): void
    {
        $scripts = $this->container->call(Customizations::class);

        $this->assertEquals('<!-- Test file; please ignore -->', $scripts);
    }

    #[Test]
    public function it_does_not_return_anything_when_the_customizations_file_does_not_exist(): void
    {
        $this->container->set('customizations_file', 'NONEXISTENT_FILE');

        $scripts = $this->container->call(Customizations::class);

        $this->assertEquals('', $scripts);
    }
}
