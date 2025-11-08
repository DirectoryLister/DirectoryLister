<?php

declare(strict_types=1);

namespace Tests\Bootstrap;

use App\Bootstrap\Builder;
use DI\Container;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Slim\App;
use Tests\TestCase;

#[CoversClass(Builder::class)]
class BuilderTest extends TestCase
{
    /** Path to the compiled container. */
    private const COMPILED_CONTAINER_PATH = 'cache/CompiledContainer.php';

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
    public function it_can_create_the_container(): void
    {
        $container = Builder::createContainer(
            $this->filePath('config'),
            $this->filePath('cache'),
        );

        $this->assertInstanceOf(Container::class, $container);
    }

    #[Test]
    public function it_creates_the_container_and_caches_it_by_default(): void
    {
        putenv('COMPILE_CONTAINER=');

        $container = Builder::createContainer(
            $this->filePath('config'),
            $this->filePath('cache')
        );

        $this->assertInstanceOf(Container::class, $container);
        $this->assertFileExists($this->compiledContainerPath);
    }

    #[Test]
    public function it_does_not_cache_the_container_when_explicitly_disabled(): void
    {
        putenv('COMPILE_CONTAINER=false');

        $container = Builder::createContainer(
            $this->filePath('config'),
            $this->filePath('cache')
        );

        $this->assertInstanceOf(Container::class, $container);
        $this->assertFileDoesNotExist($this->compiledContainerPath);
    }

    #[Test]
    public function it_does_not_cache_the_container_when_debug_is_enabled(): void
    {
        putenv('APP_DEBUG=true');

        $container = Builder::createContainer(
            $this->filePath('config'),
            $this->filePath('cache')
        );

        $this->assertInstanceOf(Container::class, $container);
        $this->assertFileDoesNotExist($this->compiledContainerPath);
    }

    #[Test]
    public function it_can_create_the_application_from_the_container(): void
    {
        $app = Builder::createApp($this->container);

        $this->assertInstanceOf(App::class, $app);
        $this->assertSame($this->container, $app->getContainer());
    }
}
