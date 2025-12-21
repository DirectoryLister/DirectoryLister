<?php

declare(strict_types=1);

namespace Tests\Functions;

use App\Functions\ModifiedTime;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Finder\SplFileInfo;
use Tests\TestCase;

#[CoversClass(ModifiedTime::class)]
class ModifiedTimeTest extends TestCase
{
    #[Test]
    public function it_can_return_the_modified_time_for_a_file(): void
    {
        $this->container->set('timezone', 'UTC');

        $file = $this->createMock(SplFileInfo::class);
        $file->method('getMTime')->willReturn(516976496);

        $modifiedTime = $this->container->call(ModifiedTime::class, ['file' => $file]);

        $this->assertEquals('1986-05-20 12:34:56', $modifiedTime);
    }
}
