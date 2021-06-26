<?php

namespace Tests\Bootstrap;

use App\Bootstrap\BootManager;
use DI\Container;
use Tests\TestCase;

/** @covers \App\Bootstrap\BootManager */
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

    /** @test */
    public function it_caches_the_container_by_default(): void
    {
        putenv('COMPILE_CONTAINER=');

        $container = BootManager::createContainer(
            $this->filePath('app/config'),
            $this->filePath('app/cache')
        );

        $this->assertInstanceOf(Container::class, $container);
        $this->assertFileExists($this->compiledContainerPath);
    }

    /** @test */
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

    /** @test */
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
