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

    protected function tearDown(): void
    {
        if (file_exists($this->filePath(self::COMPILED_CONTAINER_PATH))) {
            unlink($this->filePath(self::COMPILED_CONTAINER_PATH));
        }

        parent::tearDown();
    }

    /** @test */
    public function it_caches_the_container_when_compile_container_is_true(): void
    {
        putenv('APP_DEBUG=false');
        putenv('COMPILE_CONTAINER=true');

        $container = BootManager::createContainer(
            $this->filePath('app/config'),
            $this->filePath('app/cache')
        );

        $this->assertInstanceOf(Container::class, $container);
        $this->assertFileExists($this->filePath(self::COMPILED_CONTAINER_PATH));
    }

    /** @test */
    public function it_does_not_cache_the_container_when_compilation_is_explicitly_disabled(): void
    {
        putenv('APP_DEBUG=false');
        putenv('COMPILE_CONTAINER=false');

        $container = BootManager::createContainer(
            $this->filePath('app/config'),
            $this->filePath('app/cache')
        );

        $this->assertInstanceOf(Container::class, $container);
        $this->assertFileDoesNotExist($this->filePath('app/cache/CompiledContainer.php'));
    }

    /** @test */
    public function it_does_not_cache_the_container_when_debug_is_enabled(): void
    {
        putenv('APP_DEBUG=true');
        putenv('COMPILE_CONTAINER=true');

        $container = BootManager::createContainer(
            $this->filePath('app/config'),
            $this->filePath('app/cache')
        );

        $this->assertInstanceOf(Container::class, $container);
        $this->assertFileDoesNotExist($this->filePath('app/cache/CompiledContainer.php'));
    }
}
