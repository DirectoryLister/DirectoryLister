<?php

declare(strict_types=1);

namespace Tests\Bootstrap;

use App\Bootstrap\BootManager;
use DI\Container;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(BootManager::class)]
class BootManagerTest extends TestCase
{
    /** Path to the compiled container. */
    private const COMPILED_CONTAINER_PATH = 'app/cache/CompiledContainer.php';

    /** @var string The compiled container path */
    private $compiledContainerPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->compiledContainerPath = $this->filePath(self::COMPILED_CONTAINER_PATH);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->compiledContainerPath)) {
            unlink($this->compiledContainerPath);
        }

        parent::tearDown();
    }

    #[Test]
    public function it_caches_the_container_by_default(): void
    {
        putenv('COMPILE_CONTAINER');

        $container = BootManager::createContainer(
            $this->filePath('app/config'),
            $this->filePath('app/cache')
        );

        $this->assertInstanceOf(Container::class, $container);
        $this->assertFileExists($this->compiledContainerPath);
    }

    #[Test]
    public function it_does_not_cache_the_container_when_explicitly_disabled(): void
    {
        putenv('COMPILE_CONTAINER=false');

        $container = BootManager::createContainer(
            $this->filePath('app/config'),
            $this->filePath('app/cache')
        );

        $this->assertInstanceOf(Container::class, $container);
        $this->assertFileDoesNotExist($this->compiledContainerPath);
    }

    #[Test]
    public function it_does_not_cache_the_container_when_debug_is_enabled(): void
    {
        putenv('APP_DEBUG=true');

        $container = BootManager::createContainer(
            $this->filePath('app/config'),
            $this->filePath('app/cache')
        );

        $this->assertInstanceOf(Container::class, $container);
        $this->assertFileDoesNotExist($this->compiledContainerPath);
    }
}
